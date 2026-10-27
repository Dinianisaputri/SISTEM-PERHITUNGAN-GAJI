<?php

namespace App\Services;

use App\Models\AttendanceLog;
use Carbon\Carbon;

class AttendanceLogService
{
    public function save(
        string $pin,
        Carbon|string $punchedAt,
        ?int $status = null,
        ?int $verify = null,
        ?string $workCode = null,
        string $source = 'unknown',
        ?string $deviceSn = null,
        ?string $rawData = null,
        ?string $sourceIp = null,
    ): array {
        $pin = trim($pin);

        if ($pin === '') {
            return [
                'created' => false,
                'duplicate' => false,
                'record' => null,
            ];
        }

        if (!$punchedAt instanceof Carbon) {
            $punchedAt = Carbon::parse($punchedAt);
        }

        $punchedAt = $punchedAt->copy()->microsecond(0);

        $existing = AttendanceLog::query()
            ->where('pin', $pin)
            ->where('punched_at', $punchedAt)
            ->where(function ($query) use ($status) {
                if ($status === null) {
                    $query->whereNull('status');
                } else {
                    $query->where('status', $status);
                }
            })
            ->where(function ($query) use ($verify) {
                if ($verify === null) {
                    $query->whereNull('verify');
                } else {
                    $query->where('verify', $verify);
                }
            })
            ->first();

        if ($existing) {
            return [
                'created' => false,
                'duplicate' => true,
                'record' => $existing,
            ];
        }

        if ($this->isCrossImportDuplicate($pin, $punchedAt, $status, $verify, $source)) {
            return [
                'created' => false,
                'duplicate' => true,
                'record' => null,
            ];
        }

        $eventHash = $this->makeEventHash(
            $pin,
            $punchedAt,
            $status,
            $verify
        );

        $existingByHash = AttendanceLog::query()
            ->where('event_hash', $eventHash)
            ->first();

        if ($existingByHash) {
            return [
                'created' => false,
                'duplicate' => true,
                'record' => $existingByHash,
            ];
        }

        $record = AttendanceLog::create([
            'device_sn' => $deviceSn,
            'pin' => $pin,
            'punched_at' => $punchedAt,
            'status' => $status,
            'verify' => $verify,
            'work_code' => $workCode,
            'raw_data' => $rawData,
            'event_hash' => $eventHash,
            'source_ip' => $sourceIp,
            'received_at' => now(),
            'source' => $source,
        ]);

        return [
            'created' => true,
            'duplicate' => false,
            'record' => $record,
        ];
    }

    /**
     * Simpan banyak data sekaligus.
     *
     * Digunakan untuk import DAT/Excel agar tidak melakukan
     * query satu per satu.
     */
    public function saveMany(array $items): array
    {
        if (empty($items)) {
            return [
                'created' => 0,
                'duplicate' => 0,
                'failed' => 0,
            ];
        }

        $normalized = [];

        foreach ($items as $item) {
            try {
                $pin = trim((string) ($item['pin'] ?? ''));

                if ($pin === '') {
                    continue;
                }

                $punchedAt = $item['punched_at'] instanceof Carbon
                    ? $item['punched_at']->copy()
                    : Carbon::parse($item['punched_at']);

                $punchedAt->microsecond(0);

                $status = array_key_exists('status', $item)
                    ? $item['status']
                    : null;

                $verify = array_key_exists('verify', $item)
                    ? $item['verify']
                    : null;

                $workCode = $item['work_code'] ?? null;

                $eventHash = $this->makeEventHash(
                    $pin,
                    $punchedAt,
                    $status,
                    $verify
                );

                $key = $this->makeIdentityKey(
                    $pin,
                    $punchedAt,
                    $status,
                    $verify
                );

                $normalized[] = [
                    'identity_key' => $key,
                    'pin' => $pin,
                    'punched_at' => $punchedAt,
                    'status' => $status,
                    'verify' => $verify,
                    'work_code' => $workCode,
                    'source' => $item['source'] ?? 'unknown',
                    'device_sn' => $item['device_sn'] ?? null,
                    'raw_data' => $item['raw_data'] ?? null,
                    'source_ip' => $item['source_ip'] ?? null,
                    'event_hash' => $eventHash,
                ];
            } catch (\Throwable $e) {
                continue;
            }
        }

        if (empty($normalized)) {
            return [
                'created' => 0,
                'duplicate' => 0,
                'failed' => count($items),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Hilangkan duplikat di dalam file yang sama
        |--------------------------------------------------------------------------
        */

        $uniqueItems = [];
        $duplicate = 0;

        foreach ($normalized as $item) {
            if (isset($uniqueItems[$item['identity_key']])) {
                $duplicate++;
                continue;
            }

            $uniqueItems[$item['identity_key']] = $item;
        }

        $normalized = array_values($uniqueItems);

        /*
        |--------------------------------------------------------------------------
        | Cek data yang sudah ada di database secara batch
        |--------------------------------------------------------------------------
        */

        $created = 0;

        foreach (array_chunk($normalized, 500) as $chunk) {

            $conditions = [];

            foreach ($chunk as $item) {
                $conditions[] = [
                    'pin' => $item['pin'],
                    'punched_at' => $item['punched_at']->format('Y-m-d H:i:s'),
                    'status' => $item['status'],
                    'verify' => $item['verify'],
                ];
            }

            /*
             * Ambil rentang waktu chunk supaya query database tetap ringan.
             */
            $dates = array_column($conditions, 'punched_at');

            $minDate = Carbon::parse(min($dates))
                ->startOfMinute()
                ->subSeconds(5)
                ->format('Y-m-d H:i:s');
            $maxDate = Carbon::parse(max($dates))
                ->endOfMinute()
                ->addSeconds(5)
                ->format('Y-m-d H:i:s');

            $existingQuery = AttendanceLog::query()
                ->whereBetween('punched_at', [
                    $minDate,
                    $maxDate,
                ]);

            $existing = $existingQuery
                ->get([
                    'pin',
                    'punched_at',
                    'status',
                    'verify',
                    'source',
                ]);

            $existingKeys = [];
            $excelPunches = [];
            $datPunches = [];
            $excelPunchTimes = [];
            $datPunchTimes = [];

            foreach ($existing as $row) {
                $punchedAt = Carbon::parse($row->punched_at);
                $existingKeys[
                    $this->makeIdentityKey(
                        $row->pin,
                        $punchedAt,
                        $row->status,
                        $row->verify
                    )
                ] = true;

                $minuteKey = $this->makeMinuteIdentityKey(
                    $row->pin,
                    $punchedAt
                );

                if (
                    $row->source === 'excel' &&
                    $row->status === null &&
                    $row->verify === null
                ) {
                    $excelPunches[$minuteKey] = true;
                    $excelPunchTimes[$row->pin][] = $punchedAt->timestamp;
                } elseif ($row->source === 'dat') {
                    $datPunches[$minuteKey] = true;
                    $datPunchTimes[$row->pin][] = $punchedAt->timestamp;
                }
            }

            $insertData = [];

            foreach ($chunk as $item) {
                $minuteKey = $this->makeMinuteIdentityKey(
                    $item['pin'],
                    $item['punched_at']
                );
                $crossImportDuplicate =
                    $item['source'] === 'dat' &&
                    (
                        isset($excelPunches[$minuteKey]) ||
                        $this->hasPunchWithinSeconds(
                            $excelPunchTimes,
                            $item['pin'],
                            $item['punched_at'],
                            5
                        )
                    );

                if (
                    !$crossImportDuplicate &&
                    $item['source'] === 'excel' &&
                    $item['status'] === null &&
                    $item['verify'] === null &&
                    (
                        isset($datPunches[$minuteKey]) ||
                        $this->hasPunchWithinSeconds(
                            $datPunchTimes,
                            $item['pin'],
                            $item['punched_at'],
                            5
                        )
                    )
                ) {
                    $crossImportDuplicate = true;
                }

                if (
                    isset($existingKeys[$item['identity_key']]) ||
                    $crossImportDuplicate
                ) {
                    $duplicate++;
                    continue;
                }

                $insertData[] = [
                    'device_sn' => $item['device_sn'],
                    'pin' => $item['pin'],
                    'punched_at' => $item['punched_at']->format(
                        'Y-m-d H:i:s'
                    ),
                    'status' => $item['status'],
                    'verify' => $item['verify'],
                    'work_code' => $item['work_code'],
                    'raw_data' => $item['raw_data'],
                    'event_hash' => $item['event_hash'],
                    'source_ip' => $item['source_ip'],
                    'received_at' => now(),
                    'source' => $item['source'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $existingKeys[$item['identity_key']] = true;

                if (
                    $item['source'] === 'excel' &&
                    $item['status'] === null &&
                    $item['verify'] === null
                ) {
                    $excelPunches[$minuteKey] = true;
                    $excelPunchTimes[$item['pin']][] = $item['punched_at']->timestamp;
                } elseif ($item['source'] === 'dat') {
                    $datPunches[$minuteKey] = true;
                    $datPunchTimes[$item['pin']][] = $item['punched_at']->timestamp;
                }
            }

            if (!empty($insertData)) {
                foreach (array_chunk($insertData, 500) as $insertChunk) {
                    AttendanceLog::insert($insertChunk);

                    $created += count($insertChunk);
                }
            }
        }

        return [
            'created' => $created,
            'duplicate' => $duplicate,
            'failed' => max(
                count($items) - $created - $duplicate,
                0
            ),
        ];
    }

    private function makeIdentityKey(
        string $pin,
        Carbon $punchedAt,
        ?int $status,
        ?int $verify
    ): string {
        return implode('|', [
            trim($pin),
            $punchedAt->format('Y-m-d H:i:s'),
            $status === null ? 'NULL' : (string) $status,
            $verify === null ? 'NULL' : (string) $verify,
        ]);
    }

    private function makeMinuteIdentityKey(
        string $pin,
        Carbon $punchedAt
    ): string {
        return trim($pin) . '|' . $punchedAt->format('Y-m-d H:i');
    }

    private function hasPunchWithinSeconds(
        array $punchTimes,
        string $pin,
        Carbon $punchedAt,
        int $tolerance
    ): bool {
        foreach ($punchTimes[$pin] ?? [] as $timestamp) {
            if (abs($punchedAt->timestamp - $timestamp) <= $tolerance) {
                return true;
            }
        }

        return false;
    }

    private function isCrossImportDuplicate(
        string $pin,
        Carbon $punchedAt,
        ?int $status,
        ?int $verify,
        string $source
    ): bool {
        $query = AttendanceLog::query()
            ->where('pin', $pin)
            ->whereBetween('punched_at', [
                $punchedAt->copy()->subSeconds(5)->format('Y-m-d H:i:s'),
                $punchedAt->copy()->addSeconds(5)->format('Y-m-d H:i:s'),
            ]);

        if ($source === 'dat') {
            $query->where('source', 'excel')
                ->whereNull('status')
                ->whereNull('verify');
        } elseif ($source === 'excel' && $status === null && $verify === null) {
            $query->where('source', 'dat');
        } else {
            return false;
        }

        return $query->exists();
    }

    private function makeEventHash(
        string $pin,
        Carbon $punchedAt,
        ?int $status,
        ?int $verify
    ): string {
        return hash(
            'sha256',
            implode('|', [
                'v2',
                trim($pin),
                $punchedAt->format('Y-m-d H:i:s'),
                $status === null ? '' : $status,
                $verify === null ? '' : $verify,
            ])
        );
    }
}