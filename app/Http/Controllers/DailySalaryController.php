<?php

namespace App\Http\Controllers;

use App\Models\DailySalary;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailySalaryController extends Controller
{
    /**
     * Halaman Gaji Harian
     */
    public function index(Request $request)
    {
        $periodeMulai = $request->filled('periode_mulai')
            ? Carbon::parse($request->periode_mulai)
            : now()->startOfWeek();

        $periodeSelesai = $request->filled('periode_selesai')
            ? Carbon::parse($request->periode_selesai)
            : now()->endOfWeek();

        $employees = Employee::query()
            ->where('jenis_karyawan', 'Harian')
            ->where('is_active', true)
            ->with([
                'attendanceLogs' => function ($query) use (
                    $periodeMulai,
                    $periodeSelesai
                ) {
                    $query->whereBetween('punched_at', [
                        $periodeMulai->copy()->startOfDay(),
                        $periodeSelesai->copy()->endOfDay(),
                    ])
                    ->orderBy('punched_at');
                }
            ])
            ->orderBy('name')
            ->get();

        $dataGaji = $employees->map(function ($employee) {
            $tanggalKerja = $employee->attendanceLogs
                ->filter(fn ($log) => !is_null($log->punched_at))
                ->map(fn ($log) => $log->punched_at->format('Y-m-d'))
                ->unique()
                ->values();

            return [
                'employee' => $employee,
                'jumlah_hari_kerja' => $tanggalKerja->count(),
                'tanggal_kerja' => $tanggalKerja,
                'attendance_logs' => $employee->attendanceLogs,
            ];
        });

        return view('daily_salaries.index', compact(
            'dataGaji',
            'periodeMulai',
            'periodeSelesai'
        ));
    }

    /**
     * Simpan hasil perhitungan Gaji Harian
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
            ],

            'periode_mulai' => [
                'required',
                'date',
            ],

            'periode_selesai' => [
                'required',
                'date',
                'after_or_equal:periode_mulai',
            ],

            'upah_harian' => [
                'required',
                'numeric',
                'min:0',
            ],

            'lembur_biasa' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tarif_lembur_biasa' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'lembur_hari_libur' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tarif_lembur_hari_libur' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'lembur_um' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tarif_lembur_um' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'lain_lain' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'potongan' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        $employee = Employee::findOrFail(
            $validated['employee_id']
        );

        // Pastikan hanya karyawan Harian
        if ($employee->jenis_karyawan !== 'Harian') {
            return back()
                ->withInput()
                ->withErrors([
                    'employee_id' =>
                        'Karyawan yang dipilih bukan karyawan Harian.'
                ]);
        }

        $periodeMulai = Carbon::parse(
            $validated['periode_mulai']
        );

        $periodeSelesai = Carbon::parse(
            $validated['periode_selesai']
        );

        /*
         * Ambil fingerprint pada periode yang dipilih.
         */
        $attendanceLogs = $employee->attendanceLogs()
            ->whereBetween('punched_at', [
                $periodeMulai->copy()->startOfDay(),
                $periodeSelesai->copy()->endOfDay(),
            ])
            ->get();

        /*
         * Satu tanggal dihitung satu hari kerja,
         * walaupun ada banyak scan pada tanggal tersebut.
         */
        $tanggalKerja = $attendanceLogs
            ->filter(fn ($log) => !is_null($log->punched_at))
            ->map(fn ($log) => $log->punched_at->format('Y-m-d'))
            ->unique()
            ->values();

        $jumlahHariKerja = $tanggalKerja->count();

        /*
         * Tarif.
         */
        $upahHarian = (float) $validated['upah_harian'];

        $lemburBiasa = (float) (
            $validated['lembur_biasa'] ?? 0
        );

        $tarifLemburBiasa = (float) (
            $validated['tarif_lembur_biasa'] ?? 0
        );

        $lemburHariLibur = (float) (
            $validated['lembur_hari_libur'] ?? 0
        );

        $tarifLemburHariLibur = (float) (
            $validated['tarif_lembur_hari_libur'] ?? 0
        );

        $lemburUm = (float) (
            $validated['lembur_um'] ?? 0
        );

        $tarifLemburUm = (float) (
            $validated['tarif_lembur_um'] ?? 0
        );

        $lainLain = (float) (
            $validated['lain_lain'] ?? 0
        );

        $potongan = (float) (
            $validated['potongan'] ?? 0
        );

        /*
         * Perhitungan.
         */
        $totalUpahHarian =
            $jumlahHariKerja * $upahHarian;

        $totalLemburBiasa =
            $lemburBiasa * $tarifLemburBiasa;

        $totalLemburHariLibur =
            $lemburHariLibur * $tarifLemburHariLibur;

        $totalLemburUm =
            $lemburUm * $tarifLemburUm;

        $totalGaji =
            $totalUpahHarian
            + $totalLemburBiasa
            + $totalLemburHariLibur
            + $totalLemburUm
            + $lainLain
            - $potongan;

        /*
         * Update kalau periode tersebut sudah pernah dihitung.
         * Create kalau belum ada.
         */
        $dailySalary = DailySalary::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'periode_mulai' => $periodeMulai->toDateString(),
                'periode_selesai' => $periodeSelesai->toDateString(),
            ],
            [
                'jumlah_hari_kerja' => $jumlahHariKerja,

                'upah_harian' => $upahHarian,
                'total_upah_harian' => $totalUpahHarian,

                'lembur_biasa' => $lemburBiasa,
                'tarif_lembur_biasa' => $tarifLemburBiasa,
                'total_lembur_biasa' => $totalLemburBiasa,

                'lembur_hari_libur' => $lemburHariLibur,
                'tarif_lembur_hari_libur' =>
                    $tarifLemburHariLibur,
                'total_lembur_hari_libur' =>
                    $totalLemburHariLibur,

                'lembur_um' => $lemburUm,
                'tarif_lembur_um' => $tarifLemburUm,
                'total_lembur_um' => $totalLemburUm,

                'lain_lain' => $lainLain,
                'potongan' => $potongan,

                'total_gaji' => $totalGaji,
            ]
        );

        return redirect()
            ->route('daily_salaries.index', [
                'periode_mulai' =>
                    $periodeMulai->toDateString(),

                'periode_selesai' =>
                    $periodeSelesai->toDateString(),
            ])
            ->with(
                'success',
                'Perhitungan Gaji Harian berhasil disimpan.'
            );
    }
}