<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeWorkSchedule;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeWorkScheduleImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $pin = trim((string) ($row['id'] ?? ''));
            $tanggal = $row['tanggal'] ?? null;
            $kodeJadwal = trim((string) ($row['jam_kerja'] ?? ''));

            if ($pin === '' || !$tanggal || $kodeJadwal === '') {
                continue;
            }

            /*
             * Cari karyawan berdasarkan PIN fingerprint.
             */
            $employee = Employee::where(
                'fingerprint_pin',
                $pin
            )->first();

            if (!$employee) {
                continue;
            }

            /*
             * Cari master jadwal berdasarkan
             * kolom "Jam Kerja" dari file KHL.
             */
            $schedule = WorkSchedule::where(
                'kode',
                $kodeJadwal
            )->first();

            if (!$schedule) {
                continue;
            }

            /*
             * Normalisasi tanggal Excel.
             */
            try {
                if (is_numeric($tanggal)) {
                    $tanggal = Carbon::createFromTimestamp(
                        \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($tanggal)
                    )->format('Y-m-d');
                } else {
                    $tanggal = Carbon::parse($tanggal)->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                continue;
            }

            /*
             * Satu karyawan hanya boleh punya
             * satu jadwal pada satu tanggal.
             */
            EmployeeWorkSchedule::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'tanggal' => $tanggal,
                ],
                [
                    'work_schedule_id' => $schedule->id,
                    'keterangan' => 'Import dari data KHL HRD',
                ]
            );
        }
    }
}