<?php

namespace Database\Seeders;

use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;

class WorkScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
        
        [
                'kode' => 'DAPUR_1',
                'nama' => 'Dapur 1',
                'jam_masuk' => '07:00',
                'jam_pulang' => '12:00',
                'keterangan' => 'Jadwal kerja Dapur',
                'is_active' => true,
            ],
            [
                'kode' => 'OFFICE 1',
                'nama' => 'Office 1',
                'jam_masuk' => '08:00',
                'jam_pulang' => '16:00',
                'keterangan' => 'Jadwal kerja Office',
                'is_active' => true,
            ],
            [
                'kode' => 'P10',
                'nama' => 'P10',
                'jam_masuk' => '10:00',
                'jam_pulang' => '18:00',
                'keterangan' => null,
                'is_active' => true,
            ],
            [
                'kode' => 'P16',
                'nama' => 'P16',
                'jam_masuk' => '16:00',
                'jam_pulang' => '00:00',
                'keterangan' => 'Shift melewati tengah malam',
                'is_active' => true,
            ],
            [
                'kode' => 'P8.5',
                'nama' => 'P8.5',
                'jam_masuk' => '08:00',
                'jam_pulang' => '14:00',
                'keterangan' => null,
                'is_active' => true,
            ],
            [
                'kode' => 'P9',
                'nama' => 'P9',
                'jam_masuk' => '09:00',
                'jam_pulang' => '17:00',
                'keterangan' => null,
                'is_active' => true,
            ],
            [
                'kode' => 'PEST CONTROL',
                'nama' => 'Pest Control',
                'jam_masuk' => '05:00',
                'jam_pulang' => '13:00',
                'keterangan' => 'Jadwal kerja Pest Control',
                'is_active' => true,
            ],
            [
                'kode' => 'PRD SR 1',
                'nama' => 'Produksi Shift 1',
                'jam_masuk' => '07:00',
                'jam_pulang' => '15:00',
                'keterangan' => 'Produksi Shift 1',
                'is_active' => true,
            ],
            [
                'kode' => 'PRD SR 2',
                'nama' => 'Produksi Shift 2',
                'jam_masuk' => '08:00',
                'jam_pulang' => '16:00',
                'keterangan' => 'Produksi Shift 2',
                'is_active' => true,
            ],
            [
                'kode' => 'S',
                'nama' => 'Shift Sore',
                'jam_masuk' => '15:00',
                'jam_pulang' => '23:00',
                'keterangan' => 'Shift sore',
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            WorkSchedule::updateOrCreate(
                [
                    'kode' => $schedule['kode'],
                ],
                $schedule
            );
        }
    }
}