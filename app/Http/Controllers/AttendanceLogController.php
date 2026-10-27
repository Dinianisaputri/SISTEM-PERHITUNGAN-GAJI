<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Imports\AttendanceExcelImport;
use App\Services\AttendanceLogService;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceLogController extends Controller
{
    /**
     * Halaman Data Absensi
     */
    public function index(Request $request)
    {
        $query = AttendanceLog::query()
            ->with('employee')
            ->orderByDesc('punched_at');

        // Filter tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'punched_at',
                '>=',
                $request->tanggal_mulai
            );
        }

        // Filter tanggal selesai
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate(
                'punched_at',
                '<=',
                $request->tanggal_selesai
            );
        }

        // Filter source
        if ($request->filled('source')) {
            $query->where(
                'source',
                $request->source
            );
        }

        // Search PIN / nama
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'pin',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas('employee', function ($employee) use ($search) {
                    $employee
                        ->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'fingerprint_pin',
                            'like',
                            '%' . $search . '%'
                        );
                });
            });
        }

        // Data absensi
        $attendanceLogs = $query
            ->paginate(150)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | PIN Belum Terdaftar
        |--------------------------------------------------------------------------
        */

        $belumTerdaftar = AttendanceLog::query()
            ->whereDoesntHave('employee')
            ->select('pin')
            ->distinct()
            ->orderBy('pin')
            ->pluck('pin');

        /*
        |--------------------------------------------------------------------------
        | Alias untuk Blade
        |--------------------------------------------------------------------------
        */

        $unregisteredPins = $belumTerdaftar;

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        // Total seluruh scan
        $totalScan = AttendanceLog::count();

        // Total karyawan
        $totalKaryawan = Employee::count();

        // Total scan yang PIN-nya sudah terdaftar
        $totalTerdaftar = AttendanceLog::query()
            ->whereHas('employee')
            ->count();

        // Total PIN belum terdaftar
        $totalBelumTerdaftar = $belumTerdaftar->count();

        // Scan hari ini
        $scanHariIni = AttendanceLog::query()
            ->whereDate(
                'punched_at',
                today()
            )
            ->count();

        // Scan bulan ini
        $scanBulanIni = AttendanceLog::query()
            ->whereYear(
                'punched_at',
                now()->year
            )
            ->whereMonth(
                'punched_at',
                now()->month
            )
            ->count();

        // Jumlah PIN terdaftar yang pernah melakukan scan
        $pinTerdaftar = AttendanceLog::query()
            ->whereHas('employee')
            ->distinct('pin')
            ->count('pin');

        // Alias jumlah PIN belum terdaftar
        $pinBelumTerdaftar = $totalBelumTerdaftar;

        return view(
            'attendance.index',
            compact(
                'attendanceLogs',
                'belumTerdaftar',
                'unregisteredPins',
                'totalScan',
                'totalKaryawan',
                'totalTerdaftar',
                'totalBelumTerdaftar',
                'scanHariIni',
                'scanBulanIni',
                'pinTerdaftar',
                'pinBelumTerdaftar'
            )
        );
    }

    /**
     * Halaman PIN Belum Terdaftar
     */
    public function unregistered(Request $request)
    {
        $query = AttendanceLog::query()
            ->with('employee')
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
            ->paginate(150)
            ->withQueryString();

        // Daftar PIN unik yang belum terdaftar
        $pinBelumTerdaftar = AttendanceLog::query()
            ->whereDoesntHave('employee')
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

    /**
     * Import Excel
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],
        ]);

        $import = new AttendanceExcelImport(
            app(AttendanceLogService::class)
        );

        Excel::import(
            $import,
            $request->file('file')
        );

        return redirect()
            ->route('attendance.index')
            ->with(
                'success',
                'Import Excel selesai. '
                . $import->created
                . ' data baru, '
                . $import->duplicate
                . ' duplikat dilewati, '
                . $import->failed
                . ' data gagal.'
            );
    }
public function importDat(
    Request $request,
    AttendanceLogService $attendanceService
) {
    $request->validate([
        'file' => [
            'required',
            'file',
            'max:102400',
        ],
    ]);

    $file = $request->file('file');
    $handle = fopen($file->getRealPath(), 'r');

    if (!$handle) {
        return back()->with(
            'error',
            'File DAT tidak dapat dibaca.'
        );
    }

    $items = [];
    $created = 0;
    $duplicate = 0;
    $failed = 0;

    while (($line = fgets($handle)) !== false) {
        $line = rtrim($line, "\r\n");

        if (trim($line) === '') {
            continue;
        }

        // Abaikan BOM UTF-8 yang kadang muncul pada baris pertama file export.
        $line = preg_replace('/^\xEF\xBB\xBF/', '', $line);

        // Format DAT: PIN, tanggal, waktu, status, verify, dan work code opsional.
        if (!preg_match(
            '/^\s*(\S+)\s+(\d{4}-\d{2}-\d{2})\s+(\d{2}:\d{2}:\d{2})\s+(.+?)\s*$/',
            $line,
            $matches
        )) {
            $failed++;
            continue;
        }

        $pin = trim($matches[1]);
        $dateTime = $matches[2] . ' ' . $matches[3];

        // Batasi menjadi tiga kolom agar work code boleh berisi spasi.
        $values = preg_split('/\s+/', trim($matches[4]), 3);

        if (
            $pin === '' ||
            count($values) < 2 ||
            !is_numeric($values[0]) ||
            !is_numeric($values[1])
        ) {
            $failed++;
            continue;
        }

        try {
            $punchedAt = \Carbon\Carbon::createFromFormat(
                '!Y-m-d H:i:s',
                $dateTime
            );
        } catch (\Throwable $e) {
            $failed++;
            continue;
        }

        if (!$punchedAt) {
            $failed++;
            continue;
        }

        $status = (int) $values[0];
        $verify = (int) $values[1];
        $workCode = isset($values[2]) && trim($values[2]) !== ''
            ? trim($values[2])
            : null;


        $items[] = [
            'pin' => $pin,
            'punched_at' => $punchedAt,
            'status' => $status,
            'verify' => $verify,
            'work_code' => $workCode,
            'source' => 'dat',
            'device_sn' => null,
            'source_ip' => null,
            'raw_data' => $line,
        ];

        // Simpan per 500 data agar penggunaan memori terkendali.
        if (count($items) >= 500) {
            $batchResult = $attendanceService->saveMany($items);
            $items = [];

            $created += $batchResult['created'] ?? 0;
            $duplicate += $batchResult['duplicate'] ?? 0;
            $failed += $batchResult['failed'] ?? 0;
        }
    }

    fclose($handle);

    if (!empty($items)) {
        $batchResult = $attendanceService->saveMany($items);

        $created += $batchResult['created'] ?? 0;
        $duplicate += $batchResult['duplicate'] ?? 0;
        $failed += $batchResult['failed'] ?? 0;
    }

    return redirect()
        ->route('attendance.index')
        ->with(
            'success',
            "Import DAT selesai. {$created} data baru, "
            . "{$duplicate} duplikat dilewati, "
            . "{$failed} data gagal."
        );
}
        }