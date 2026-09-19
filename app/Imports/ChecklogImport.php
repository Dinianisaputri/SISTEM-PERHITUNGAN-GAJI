<?php

namespace App\Imports;

use App\Models\BoronganEmployee;
use App\Models\Checklog;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ChecklogImport implements
    ToCollection,
    WithHeadingRow,
    WithChunkReading,
    WithMultipleSheets
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $noId = trim((string) ($row['no_id'] ?? ''));

            if ($noId === '') {
                continue;
            }

            /*
             * Cari apakah No. ID ini terdaftar
             * sebagai karyawan borongan.
             */
            $employee = BoronganEmployee::where('no_id', $noId)->first();

            /*
             * Kalau tidak ada di master,
             * jangan dimasukkan.
             */
            if (!$employee) {
                continue;
            }

            $tanggal = $this->parseDate($row['tanggal'] ?? null);

            if (!$tanggal) {
                continue;
            }

            $scanMasuk = $this->parseTime($row['scan_masuk'] ?? null);
            $scanPulang = $this->parseTime($row['scan_pulang'] ?? null);

            $totalJamKerja = 0;
            $gaji = 0;

            /*
             * Hitung sementara tanpa istirahat.
             */
            if ($scanMasuk && $scanPulang) {

                $masuk = Carbon::createFromFormat(
                    'H:i',
                    $scanMasuk
                );

                $pulang = Carbon::createFromFormat(
                    'H:i',
                    $scanPulang
                );

                /*
                 * Shift lewat tengah malam.
                 *
                 * Contoh:
                 * 16:02 -> 04:45
                 */
                if ($pulang->lessThanOrEqualTo($masuk)) {
                    $pulang->addDay();
                }

                $totalMinutes = $masuk->diffInMinutes($pulang);

                $totalJamKerja = $totalMinutes / 60;

                $gaji = $totalJamKerja * 18000;
            }

            Checklog::create([
                'borongan_employee_id' => $employee->id,

                'tanggal' => $tanggal,

                'jam_masuk' => $this->parseTime(
                    $row['jam_masuk'] ?? null
                ),

                'jam_pulang' => $this->parseTime(
                    $row['jam_pulang'] ?? null
                ),

                'scan_masuk' => $scanMasuk,

                'scan_pulang' => $scanPulang,

                /*
                 * Istirahat nanti diinput HRD.
                 */
                'jam_istirahat' => 0,

                'total_jam_kerja' => $totalJamKerja,

                'hourly_rate' => 18000,

                'gaji' => $gaji,

                /*
                 * NULL = belum masuk arsip.
                 */
                'salary_archive_id' => null,
            ]);
        }
    }

    public function sheets(): array
    {
        return [
            'DATA INPUT' => $this,
        ];
    }

    private function parseDate($value)
{
    if ($value === null || $value === '') {
        return null;
    }

    try {

        // Jika tanggal berupa angka serial Excel
        if (is_numeric($value)) {
            return Carbon::create(1899, 12, 30)
                ->addDays((int) $value)
                ->format('Y-m-d');
        }

        $value = trim((string) $value);

        // Format Excel: dd/mm/yyyy
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            return Carbon::createFromFormat(
                'd/m/Y',
                $value
            )->format('Y-m-d');
        }

        // Format alternatif yyyy-mm-dd
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return Carbon::createFromFormat(
                'Y-m-d',
                $value
            )->format('Y-m-d');
        }

        return null;

    } catch (\Exception $e) {
        return null;
    }
}

    private function parseTime($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {

            if (is_numeric($value)) {

                $totalSeconds = round(
                    $value * 24 * 60 * 60
                );

                $hours = floor($totalSeconds / 3600);

                $minutes = floor(
                    ($totalSeconds % 3600) / 60
                );

                return sprintf(
                    '%02d:%02d',
                    $hours,
                    $minutes
                );
            }

            return Carbon::parse(
                trim((string) $value)
            )->format('H:i');

        } catch (\Exception $e) {
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}