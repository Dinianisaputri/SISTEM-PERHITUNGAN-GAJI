<?php

namespace Database\Seeders;

use App\Models\BoronganEmployee;
use Illuminate\Database\Seeder;

class BoronganEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'no_id' => '170',
                'nik' => null,
                'name' => 'BAY ANDI LUKMAN',
                'bagian' => null,
            ],
            [
                'no_id' => '1005',
                'nik' => null,
                'name' => 'DINAL SUGENG FRAHANA',
                'bagian' => null,
            ],
            [
                'no_id' => '1006',
                'nik' => null,
                'name' => 'ASYHURI',
                'bagian' => null,
            ],
            [
                'no_id' => '1007',
                'nik' => null,
                'name' => 'BAGAS SOBIRIN',
                'bagian' => null,
            ],
            [
                'no_id' => '1008',
                'nik' => null,
                'name' => 'FEBRIAWAN DWI SULTHONI',
                'bagian' => null,
            ],
            [
                'no_id' => '1009',
                'nik' => null,
                'name' => 'DIMAS ROBERT SAPUTRA',
                'bagian' => null,
            ],
        ];

        foreach ($employees as $employee) {
            BoronganEmployee::updateOrCreate(
                ['no_id' => $employee['no_id']],
                $employee
            );
        }
    }
}