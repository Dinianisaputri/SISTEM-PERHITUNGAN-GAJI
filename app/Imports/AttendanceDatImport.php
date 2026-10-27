<?php

namespace App\Imports;

use App\Services\AttendanceLogService;
use Carbon\Carbon;

class AttendanceDatImport
{
    public int $created = 0;
    public int $duplicate = 0;
    public int $failed = 0;

    public function __construct(
        protected AttendanceLogService $attendanceService
    ) {
    }

    public function import(string $filePath): void
    {
        $handle = fopen($filePath, 'r');

        if (!$handle) {
            throw new \RuntimeException(
                'File DAT tidak dapat dibaca.'
            );
        }

        while (($line = fgets($handle)) !== false) {

            $line = rtrim($line, "\r\n");

            if (trim($line) === '') {
                continue;
            }

            $this->processLine($line);
        }

        fclose($handle);
    }

    private function processLine(string $line): void
    {
        /*
        |--------------------------------------------------------------------------
        | File DAT menggunakan TAB sebagai pemisah
        |--------------------------------------------------------------------------
        |
        | Format asli:
        |
        | PIN
        | Tanggal + Jam
        | Status
        | Verify
        | Work Code
        | Field terakhir
        |
        | Contoh:
        |
        | 158    2025-10-31 15:04:23    1    1    1    0
        |
        */

        $fields = explode("\t", $line);

        /*
        |--------------------------------------------------------------------------
        | Pastikan format lengkap
        |--------------------------------------------------------------------------
        */

        if (count($fields) < 2) {
            $this->failed++;
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PIN
        |--------------------------------------------------------------------------
        */

        $pin = trim($fields[0] ?? '');

        if ($pin === '') {
            $this->failed++;
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Tanggal dan Jam
        |--------------------------------------------------------------------------
        */

        $dateTime = trim($fields[1] ?? '');

        if ($dateTime === '') {
            $this->failed++;
            return;
        }

        try {

            $punchedAt = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $dateTime
            );

        } catch (\Throwable $e) {

            $this->failed++;
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $status = isset($fields[2])
            && is_numeric(trim($fields[2]))
            ? (int) trim($fields[2])
            : null;

        /*
        |--------------------------------------------------------------------------
        | Verify
        |--------------------------------------------------------------------------
        */

        $verify = isset($fields[3])
            && is_numeric(trim($fields[3]))
            ? (int) trim($fields[3])
            : null;

        /*
        |--------------------------------------------------------------------------
        | Work Code
        |--------------------------------------------------------------------------
        */

        $workCode = isset($fields[4])
            ? trim($fields[4])
            : null;

        /*
        |--------------------------------------------------------------------------
        | Simpan data
        |--------------------------------------------------------------------------
        */

        try {

            $result = $this->attendanceService->save(
                pin: $pin,
                punchedAt: $punchedAt,
                status: $status,
                verify: $verify,
                workCode: $workCode,
                source: 'dat',
                deviceSn: null,
                rawData: $line,
            );

            if ($result['created']) {

                $this->created++;

            } elseif ($result['duplicate']) {

                $this->duplicate++;

            } else {

                $this->failed++;
            }

        } catch (\Throwable $e) {

            $this->failed++;
        }
    }
}