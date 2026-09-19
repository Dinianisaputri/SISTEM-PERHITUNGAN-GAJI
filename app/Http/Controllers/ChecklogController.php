<?php

namespace App\Http\Controllers;

use App\Imports\ChecklogImport;
use App\Models\Checklog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChecklogController extends Controller
{
 public function index(Request $request)
{
    $query = Checklog::with('employee')
        ->whereNull('salary_archive_id');

    // Filter tampilan berdasarkan tanggal
    if ($request->filled('filter_tanggal_mulai')) {
        $query->whereDate(
            'tanggal',
            '>=',
            $request->filter_tanggal_mulai
        );
    }

    if ($request->filled('filter_tanggal_selesai')) {
        $query->whereDate(
            'tanggal',
            '<=',
            $request->filter_tanggal_selesai
        );
    }

    // Filter tampilan berdasarkan nama
    if ($request->filled('nama_karyawan')) {

        $nama = $request->nama_karyawan;

        $query->whereHas('employee', function ($q) use ($nama) {
            $q->where(
                'name',
                'like',
                '%' . $nama . '%'
            );
        });
    }

    $checklogs = $query
        ->orderBy('tanggal')
        ->orderBy('borongan_employee_id')
        ->get();

    // Periode seluruh data yang belum diarsipkan
    $tanggalMulai = Checklog::whereNull('salary_archive_id')
        ->min('tanggal');

    $tanggalSelesai = Checklog::whereNull('salary_archive_id')
        ->max('tanggal');

    return view(
        'checklogs.index',
        compact(
            'checklogs',
            'tanggalMulai',
            'tanggalSelesai'
        )
    );
}
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai'
            ],
        ]);

        Checklog::whereNull('salary_archive_id')
            ->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai,
            ])
            ->delete();

        Excel::import(
            new ChecklogImport,
            $request->file('file')
        );

        Checklog::whereNull('salary_archive_id')
            ->where(function ($query) use ($request) {

                $query->where(
                    'tanggal',
                    '<',
                    $request->tanggal_mulai
                )
                ->orWhere(
                    'tanggal',
                    '>',
                    $request->tanggal_selesai
                );

            })
            ->delete();

        return redirect()
            ->route('checklogs.index')
            ->with('success', 'Data Excel berhasil diimport.');
    }


    public function hitungSemua(Request $request)
{
    $request->validate([
        'istirahat' => 'required|array',
        'istirahat.*' => [
            'required',
            'regex:/^\d+(\.[0-5][0-9])?$/'
        ],
    ], [
        'istirahat.*.regex' =>
            'Format istirahat harus seperti 0.00, 0.30, 1.00, atau 1.30.'
    ]);

    foreach ($request->istirahat as $id => $jamIstirahat) {

        $checklog = Checklog::whereNull('salary_archive_id')
            ->find($id);

        if (!$checklog) {
            continue;
        }

        /*
         * Format:
         * 1.00 = 1 jam
         * 1.30 = 1 jam 30 menit
         * 0.30 = 30 menit
         */

        $jamIstirahat = (string) $jamIstirahat;

        if (str_contains($jamIstirahat, '.')) {

            [$jam, $menit] = explode('.', $jamIstirahat);

            $jam = (int) $jam;
            $menit = (int) str_pad($menit, 2, '0');

        } else {

            $jam = (int) $jamIstirahat;
            $menit = 0;

        }

        $breakMinutes = ($jam * 60) + $menit;

        $totalJamKerja = 0;
        $gaji = 0;

        /*
         * Hitung berdasarkan Scan Masuk dan Scan Pulang
         */
        if ($checklog->scan_masuk && $checklog->scan_pulang) {

            $masuk = \Carbon\Carbon::createFromFormat(
                'H:i:s',
                $checklog->scan_masuk
            );

            $pulang = \Carbon\Carbon::createFromFormat(
                'H:i:s',
                $checklog->scan_pulang
            );

            /*
             * Jika pulang melewati tengah malam
             *
             * Contoh:
             * 16:02 -> 04:45
             */
            if ($pulang->lessThanOrEqualTo($masuk)) {
                $pulang->addDay();
            }

            // Durasi kerja dari fingerprint
            $totalMinutes = $masuk->diffInMinutes($pulang);

            // Kurangi waktu istirahat
            $workMinutes = max(
                0,
                $totalMinutes - $breakMinutes
            );

            // Ubah menit menjadi jam
            $totalJamKerja = $workMinutes / 60;

            // Tarif Rp18.000 per jam
            $gaji = $workMinutes * (18000 / 60);
        }

        $checklog->update([
            'jam_istirahat' => $jamIstirahat,
            'total_jam_kerja' => $totalJamKerja,
            'hourly_rate' => 18000,
            'gaji' => $gaji,
        ]);
    }

    return redirect()
        ->route('checklogs.index')
        ->with('success', 'Semua gaji berhasil dihitung.');
}
public function simpanArsip(Request $request)
{
    $request->validate([
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    ]);

    // Ambil semua checklog yang belum masuk arsip
    $checklogs = Checklog::whereNull('salary_archive_id')
        ->whereBetween('tanggal', [
            $request->tanggal_mulai,
            $request->tanggal_selesai,
        ])
        ->get();

    // Kalau tidak ada data
    if ($checklogs->isEmpty()) {
        return redirect()
            ->route('checklogs.index')
            ->with('error', 'Tidak ada data gaji yang dapat diarsipkan.');
    }

    // Buat nama arsip berdasarkan periode
    $tanggalMulai = \Carbon\Carbon::parse($request->tanggal_mulai)
        ->format('d-m-Y');

    $tanggalSelesai = \Carbon\Carbon::parse($request->tanggal_selesai)
        ->format('d-m-Y');

    $namaArsip = "Gaji KHL {$tanggalMulai} s/d {$tanggalSelesai}";

    // Hitung total gaji
    $totalGaji = $checklogs->sum('gaji');

    // Hitung jumlah checklog
    $totalChecklogs = $checklogs->count();

    // Hitung jumlah karyawan unik
    $totalKaryawan = $checklogs
        ->pluck('borongan_employee_id')
        ->unique()
        ->count();

    // Buat arsip
    $archive = \App\Models\SalaryArchive::create([
        'archive_name' => $namaArsip,
        'start_date' => $request->tanggal_mulai,
        'end_date' => $request->tanggal_selesai,
        'total_salary' => $totalGaji,
        'total_checklogs' => $totalChecklogs,
        'total_employees' => $totalKaryawan,
    ]);

    // Masukkan semua checklog ke arsip tersebut
    Checklog::whereIn('id', $checklogs->pluck('id'))
        ->update([
            'salary_archive_id' => $archive->id,
        ]);

    return redirect()
        ->route('checklogs.index')
        ->with('success', 'Data gaji berhasil disimpan ke arsip.');
}
}