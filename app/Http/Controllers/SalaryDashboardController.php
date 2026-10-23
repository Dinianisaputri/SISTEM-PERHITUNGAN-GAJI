<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Checklog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryDashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $startDate = Carbon::createFromFormat('Y-m', $bulan)
            ->startOfMonth();

        $endDate = Carbon::createFromFormat('Y-m', $bulan)
            ->endOfMonth();

        // =========================
        // DATA KARYAWAN
        // =========================

        $totalKaryawan = Employee::where('is_active', true)->count();

        $totalBorongan = Employee::where('is_active', true)
            ->where('jenis_karyawan', 'Borongan')
            ->count();

        $totalHarian = Employee::where('is_active', true)
            ->where('jenis_karyawan', 'Harian')
            ->count();

        $totalBulanan = Employee::where('is_active', true)
            ->where('jenis_karyawan', 'Bulanan')
            ->count();


        // =========================
        // GAJI BORONGAN
        // =========================

        $totalGajiBorongan = Checklog::whereBetween(
            'tanggal',
            [
                $startDate->toDateString(),
                $endDate->toDateString()
            ]
        )->sum('gaji');


        // =========================
        // SEMENTARA
        // =========================

        $totalGajiHarian = 0;

        $totalGajiBulanan = 0;


        // =========================
        // TOTAL
        // =========================

        $totalGaji = $totalGajiBorongan
            + $totalGajiHarian
            + $totalGajiBulanan;


        return view('salary.dashboard', compact(
            'bulan',
            'totalKaryawan',
            'totalBorongan',
            'totalHarian',
            'totalBulanan',
            'totalGajiBorongan',
            'totalGajiHarian',
            'totalGajiBulanan',
            'totalGaji'
        ));
    }
}