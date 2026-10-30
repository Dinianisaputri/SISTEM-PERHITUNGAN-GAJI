<?php

namespace App\Http\Controllers;

use App\Models\SalarySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalarySettingController extends Controller
{
    private array $defaults = [
        'borongan_per_jam' => [
            'label' => 'Tarif Borongan per Jam',
            'amount' => 18000,
            'unit' => 'jam',
        ],
        'harian_per_hari' => [
            'label' => 'Tarif Harian per Hari',
            'amount' => 127855,
            'unit' => 'hari',
        ],
        'lembur_biasa_per_jam' => [
            'label' => 'Tarif Lembur Biasa per Jam',
            'amount' => 18500,
            'unit' => 'jam',
        ],
        'lembur_libur_per_jam' => [
            'label' => 'Tarif Lembur Hari Libur per Jam',
            'amount' => 37000,
            'unit' => 'jam',
        ],
        'uang_makan_per_3_jam' => [
            'label' => 'Uang Makan per 3 Jam Lembur',
            'amount' => 11000,
            'unit' => '3 jam',
        ],
        'bpjs_ketenagakerjaan' => [
            'label' => 'Potongan BPJS Ketenagakerjaan',
            'amount' => 0,
            'unit' => 'karyawan/periode'
        ]
    ];

    public function index()
    {
        $settings = [];

        foreach ($this->defaults as $key => $default) {
            $setting = SalarySetting::firstOrCreate(
                ['key' => $key],
                $default
            );

            $settings[$key] = $setting;
        }

        return view('salary-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $rules = [];

        foreach (array_keys($this->defaults) as $key) {
            $rules[$key] = ['required', 'numeric', 'min:0', 'max:999999999'];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated) {
            foreach ($this->defaults as $key => $default) {
                SalarySetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'label' => $default['label'],
                        'amount' => $validated[$key],
                        'unit' => $default['unit'],
                    ]
                );
            }
        });

        return redirect()
            ->route('salary-settings.index')
            ->with('success', 'Pengaturan tarif gaji berhasil disimpan.');
    }
}