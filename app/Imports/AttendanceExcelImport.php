<?php

namespace App\Imports;

use App\Services\AttendanceLogService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Imports\AttendanceExcelImport;
use Maatwebsite\Excel\Facades\Excel;
class AttendanceExcelImport implements ToCollection, WithHeadingRow
{
    public int $created = 0;
    public int $duplicate = 0;
    public int $failed = 0;

    public function __construct(
        protected AttendanceLogService $attendanceService
    ) {
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $data = $row->toArray();

            $pin = $this->getValue($data, [
                'no_id',
                'no_id',
                'id',
            ]);

            $tanggal = $this->getValue($data, [
                'tanggal',
            ]);

            $scanMasuk = $this->getValue($data, [
                'scan_masuk',
            ]);

            $scanPulang = $this->getValue($data, [
                'scan_pulang',
            ]);

            if (!$pin || !$tanggal) {
                $this->failed++;
                continue;
            }

            /*
             * Simpan Scan Masuk
             */
            if ($scanMasuk) {
                try {
                    $tanggalMasuk = Carbon::createFromFormat(
                        'd/m/Y',
                        trim((string) $tanggal)
                    );

                    $jamMasuk = Carbon::createFromFormat(
                        'H:i',
                        trim((string) $scanMasuk)
                    );

                    $tanggalMasuk->setTime(
                        $jamMasuk->hour,
                        $jamMasuk->minute,
                        0
                    );

                    $result = $this->attendanceService->save(
                        pin: (string) $pin,
                        punchedAt: $tanggalMasuk,
                        status: null,
                        verify: null,
                        workCode: null,
                        source: 'excel',
                        deviceSn: null,
                        rawData: json_encode(
                            $data,
                            JSON_UNESCAPED_UNICODE
                        ),
                    );

                    $this->countResult($result);

                } catch (\Throwable $e) {
                    $this->failed++;
                }
            }

            /*
             * Simpan Scan Pulang
             */
            if ($scanPulang) {
                try {
                    $tanggalPulang = Carbon::createFromFormat(
                        'd/m/Y',
                        trim((string) $tanggal)
                    );

                    $jamPulang = Carbon::createFromFormat(
                        'H:i',
                        trim((string) $scanPulang)
                    );

                    /*
                     * Kalau jam pulang lebih kecil
                     * atau sama dengan jam masuk,
                     * berarti pulang di hari berikutnya.
                     */
                    if (
                        $scanMasuk &&
                        $jamPulang->format('H:i') <=
                        Carbon::parse($scanMasuk)->format('H:i')
                    ) {
                        $tanggalPulang->addDay();
                    }

                    $tanggalPulang->setTime(
                        $jamPulang->hour,
                        $jamPulang->minute,
                        0
                    );

                    $result = $this->attendanceService->save(
                        pin: (string) $pin,
                        punchedAt: $tanggalPulang,
                        status: null,
                        verify: null,
                        workCode: null,
                        source: 'excel',
                        deviceSn: null,
                        rawData: json_encode(
                            $data,
                            JSON_UNESCAPED_UNICODE
                        ),
                    );

                    $this->countResult($result);

                } catch (\Throwable $e) {
                    $this->failed++;
                }
            }
        }
    }

    private function countResult(array $result): void
    {
        if ($result['created']) {
            $this->created++;
        } elseif ($result['duplicate']) {
            $this->duplicate++;
        } else {
            $this->failed++;
        }
    }

    private function getValue(array $data, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (
                array_key_exists($key, $data) &&
                $data[$key] !== null &&
                trim((string) $data[$key]) !== ''
            ) {
                return $data[$key];
            }
        }

        return null;
    }
    public function importExcel(Request $request)
{
    $request->validate([
        'file' => [
            'required',
            'file',
            'mimes:xlsx,xls,csv',
            'max:10240',
        ],
    ]);

    $import = new AttendanceExcelImport(
        app(AttendanceLogService::class)
    );

    Excel::import(
        $import,
        $request->file('file')
    );

    return redirect()
        ->route('attendance.index')
        ->with(
            'success',
            'Import Excel selesai. '
            . $import->created . ' data baru, '
            . $import->duplicate . ' duplikat dilewati, '
            . $import->failed . ' data gagal.'
            
        );
}
}