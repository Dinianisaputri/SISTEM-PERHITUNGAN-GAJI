<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AttendanceLog;
use App\Models\Regulation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | DATA KARYAWAN
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | DATA FINGERPRINT / ABSENSI
        |--------------------------------------------------------------------------
        */

        $totalFingerprint = AttendanceLog::count();

        $scanHariIni = AttendanceLog::whereDate(
            'punched_at',
            Carbon::today()
        )->count();

        // PIN yang sudah melakukan scan tetapi belum terdaftar
        // di Data Karyawan
        $fingerprintBelumTerdaftar = AttendanceLog::whereDoesntHave(
            'employee'
        )
            ->distinct('pin')
            ->count('pin');


        /*
        |--------------------------------------------------------------------------
        | DATA REGULASI
        |--------------------------------------------------------------------------
        */

        $totalRegulasi = Regulation::count();

        $regulasiTerpenuhi = Regulation::where(
            'status',
            'Terpenuhi'
        )->count();

        $regulasiBelumTerpenuhi = Regulation::where(
            'status',
            'Tidak Terpenuhi'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | REGULASI YANG AKAN / SUDAH EXPIRED
        |--------------------------------------------------------------------------
        */

        $today = Carbon::today();

        $regulasiAkanExpired = Regulation::whereNotNull('masa_berlaku')
            ->whereDate('masa_berlaku', '<=', $today->copy()->addDays(30))
            ->count();


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('beranda', compact(
            'totalKaryawan',
            'totalBorongan',
            'totalHarian',
            'totalBulanan',
            'totalFingerprint',
            'scanHariIni',
            'fingerprintBelumTerdaftar',
            'totalRegulasi',
            'regulasiTerpenuhi',
            'regulasiBelumTerpenuhi',
            'regulasiAkanExpired'
        ));
    }
}