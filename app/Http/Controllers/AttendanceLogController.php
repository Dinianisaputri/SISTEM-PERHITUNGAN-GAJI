<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use Illuminate\Http\Request;

class AttendanceLogController extends Controller
{
   public function index(Request $request)
{
    $query = AttendanceLog::with('employee')
        ->orderByDesc('punched_at');

    if ($request->filled('search')) {
        $query->where(
            'pin',
            'like',
            '%' . $request->search . '%'
        );
    }

    if ($request->filled('tanggal')) {
        $query->whereDate(
            'punched_at',
            $request->tanggal
        );
    }

    $logs = $query->paginate(20)->withQueryString();

    $totalScan = AttendanceLog::count();

    $scanHariIni = AttendanceLog::whereDate(
        'punched_at',
        now()->toDateString()
    )->count();

    // Jumlah PIN unik yang belum terhubung ke Data Karyawan
    $belumTerdaftar = AttendanceLog::whereDoesntHave('employee')
        ->distinct()
        ->count('pin');

    return view('attendance.index', compact(
        'logs',
        'totalScan',
        'scanHariIni',
        'belumTerdaftar'
    ));
}
public function unregistered(Request $request)
{
    $query = AttendanceLog::with('employee')
        ->whereDoesntHave('employee')
        ->orderByDesc('punched_at');

    if ($request->filled('search')) {
        $query->where(
            'pin',
            'like',
            '%' . $request->search . '%'
        );
    }

    if ($request->filled('tanggal')) {
        $query->whereDate(
            'punched_at',
            $request->tanggal
        );
    }

    $logs = $query->paginate(20)->withQueryString();

    $pinBelumTerdaftar = AttendanceLog::whereDoesntHave('employee')
        ->select('pin')
        ->selectRaw('COUNT(*) as total_scan')
        ->selectRaw('MAX(punched_at) as terakhir_scan')
        ->groupBy('pin')
        ->orderByDesc('terakhir_scan')
        ->get();

    return view('attendance.unregistered', compact(
        'logs',
        'pinBelumTerdaftar'
    ));
}
}
