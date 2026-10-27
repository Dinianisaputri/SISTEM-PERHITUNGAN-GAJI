<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Halaman Data Fingerprint
     */
    public function index(Request $request)
    {
        $query = AttendanceLog::with('employee')
            ->orderByDesc('punched_at');

        // Filter PIN
        if ($request->filled('search')) {
            $query->where(
                'pin',
                'like',
                '%' . trim($request->search) . '%'
            );
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate(
                'punched_at',
                $request->tanggal
            );
        }

        $logs = $query
            ->paginate(150)
            ->withQueryString();

        // Total seluruh scan
        $totalScan = AttendanceLog::count();

        // Total scan hari ini
        $scanHariIni = AttendanceLog::whereDate(
            'punched_at',
            now()->toDateString()
        )->count();

        // Jumlah PIN yang belum terdaftar
        $belumTerdaftar = AttendanceLog::whereDoesntHave('employee')
            ->distinct('pin')
            ->count('pin');

        // Alias supaya Blade yang menggunakan $unregisteredPins tidak error
        $unregisteredPins = $belumTerdaftar;

        return view(
            'attendance.index',
            compact(
                'logs',
                'totalScan',
                'scanHariIni',
                'belumTerdaftar',
                'unregisteredPins'
            )
        );
    }

    /**
     * Halaman khusus PIN yang belum terdaftar
     */
    public function unregistered(Request $request)
    {
        $query = AttendanceLog::with('employee')
            ->whereDoesntHave('employee')
            ->orderByDesc('punched_at');

        // Filter PIN
        if ($request->filled('search')) {
            $query->where(
                'pin',
                'like',
                '%' . trim($request->search) . '%'
            );
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate(
                'punched_at',
                $request->tanggal
            );
        }

        $logs = $query
            ->paginate(20)
            ->withQueryString();

        // Daftar PIN unik yang belum terdaftar
        $pinBelumTerdaftar = AttendanceLog::whereDoesntHave('employee')
            ->select('pin')
            ->selectRaw('COUNT(*) as total_scan')
            ->selectRaw('MAX(punched_at) as terakhir_scan')
            ->groupBy('pin')
            ->orderByDesc('terakhir_scan')
            ->get();

        return view(
            'attendance.unregistered',
            compact(
                'logs',
                'pinBelumTerdaftar'
            )
        );
    }
}