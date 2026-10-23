<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDevice;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdmsController extends Controller
{
    /**
     * Handshake / konfigurasi dari mesin fingerprint.
     */
    public function cdataGet(Request $request)
    {
        Log::info('ADMS GET diterima', [
    'ip' => $request->ip(),
    'query' => $request->query(),
]);
        $sn = $request->query('SN');

        if (!$sn) {
            return response("OK", 200);
        }

        $this->updateDevice($request, $sn);

        return response(
            "GET OPTION FROM: {$sn}\r\n" .
            "Stamp=9999\r\n" .
            "ATTLOGStamp=9999\r\n" .
            "OPERLOGStamp=9999\r\n" .
            "ATTPHOTOStamp=9999\r\n" .
            "ErrorDelay=30\r\n" .
            "Delay=10\r\n" .
            "TransTimes=00:00;23:59\r\n" .
            "TransInterval=1\r\n" .
            "TransFlag=TransData AttLog\r\n" .
            "TimeZone=7\r\n" .
            "Realtime=1\r\n" .
            "Encrypt=None\r\n" .
            "0",
            200
        );
    }

    /**
     * Menerima data yang dikirim mesin fingerprint.
     */
    public function cdataPost(Request $request)
    {
        Log::info('ADMS POST diterima', [
    'ip' => $request->ip(),
    'query' => $request->query(),
    'body' => $request->getContent(),
]);
        $sn = $request->query('SN');
        $table = $request->query('table');

        if (!$sn) {
            return response("OK", 200);
        }

        $this->updateDevice($request, $sn);

        $rawBody = $request->getContent();

        if ($table === 'ATTLOG') {
            $this->processAttendanceLog(
                $sn,
                $rawBody,
                $request
            );
        }

        if ($table === 'OPERLOG') {
            Log::info('ADMS OPERLOG', [
                'serial_number' => $sn,
                'data' => $rawBody,
            ]);
        }

        return response("OK", 200);
    }

    /**
     * Memproses data attendance dari mesin.
     */
    private function processAttendanceLog(
        string $sn,
        string $rawBody,
        Request $request
    ): void {
        $lines = preg_split(
            "/\r\n|\n|\r/",
            trim($rawBody)
        );

        foreach ($lines as $line) {

            if (trim($line) === '') {
                continue;
            }

            $fields = explode("\t", $line);

            $pin = $fields[0] ?? null;
            $dateTime = $fields[1] ?? null;
            $status = $fields[2] ?? null;
            $verify = $fields[3] ?? null;
            $workCode = $fields[4] ?? null;

            if (!$pin || !$dateTime) {
                continue;
            }

            try {
                $punchedAt = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    trim($dateTime)
                );
            } catch (\Exception $e) {
                Log::warning('Format tanggal ADMS tidak valid', [
                    'serial_number' => $sn,
                    'line' => $line,
                ]);

                continue;
            }

            $eventHash = hash(
                'sha256',
                $sn . '|' . $line
            );

            AttendanceLog::firstOrCreate(
                [
                    'event_hash' => $eventHash,
                ],
                [
                    'device_sn' => $sn,
                    'pin' => $pin,
                    'punched_at' => $punchedAt,
                    'status' => is_numeric($status)
                        ? (int) $status
                        : null,
                    'verify' => is_numeric($verify)
                        ? (int) $verify
                        : null,
                    'work_code' => $workCode,
                    'raw_data' => $line,
                    'source_ip' => $request->ip(),
                    'received_at' => now(),
                ]
            );
        }
    }

    /**
     * Update data mesin yang berkomunikasi dengan Laravel.
     */
    private function updateDevice(
        Request $request,
        string $sn
    ): void {
        AttendanceDevice::updateOrCreate(
            [
                'serial_number' => $sn,
            ],
            [
                'name' => 'X100-C',
                'ip_address' => $request->ip(),
                'model' => 'X100-C',
                'is_active' => true,
                'last_seen_at' => now(),
            ]
        );
    }

    /**
     * Endpoint getrequest ADMS.
     */
    public function getRequest(Request $request)
    {
        return response("OK", 200);
    }

    /**
     * Endpoint devicecmd ADMS.
     */
    public function deviceCmd(Request $request)
    {
        return response("OK", 200);
    }

    /**
     * Endpoint registry ADMS.
     */
    public function registry(Request $request)
    {
        $sn = $request->query('SN');

        if ($sn) {
            $this->updateDevice($request, $sn);
        }

        return response("OK", 200);
    }
}