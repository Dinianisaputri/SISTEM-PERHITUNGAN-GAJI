<?php

namespace App\Http\Controllers;

use App\Models\SalaryArchive;
use App\Models\Checklog;
use Illuminate\Http\Request;

class SalaryArchiveController extends Controller
{
    public function index(Request $request)
{
    $query = SalaryArchive::query()
        ->with('checklogs.employee');

    // Filter tanggal mulai
    if ($request->filled('tanggal_mulai')) {
        $query->whereDate(
            'start_date',
            '>=',
            $request->tanggal_mulai
        );
    }

    // Filter tanggal selesai
    if ($request->filled('tanggal_selesai')) {
        $query->whereDate(
            'end_date',
            '<=',
            $request->tanggal_selesai
        );
    }

    // Filter nama karyawan
    if ($request->filled('nama_karyawan')) {

        $nama = $request->nama_karyawan;

        $query->whereHas('checklogs.employee', function ($q) use ($nama) {
            $q->where('name', 'like', '%' . $nama . '%');
        });
    }

    $archives = $query
        ->latest()
        ->get();

    return view(
        'salary_archives.index',
        compact('archives')
    );
}


    public function show(Request $request, SalaryArchive $salaryArchive)
{
    $query = $salaryArchive->checklogs()
        ->with('employee');

    // Filter tanggal mulai
    if ($request->filled('filter_tanggal_mulai')) {
        $query->whereDate(
            'tanggal',
            '>=',
            $request->filter_tanggal_mulai
        );
    }

    // Filter tanggal selesai
    if ($request->filled('filter_tanggal_selesai')) {
        $query->whereDate(
            'tanggal',
            '<=',
            $request->filter_tanggal_selesai
        );
    }

    // Filter nama karyawan
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

    return view(
        'salary_archives.show',
        compact(
            'salaryArchive',
            'checklogs'
        )
    );
}

    // Hapus arsip beserta seluruh data checklog di dalamnya
    public function destroy(SalaryArchive $salaryArchive)
    {
        // Hapus semua data checklog yang ada di dalam arsip
        $salaryArchive->checklogs()->delete();

        // Hapus data arsip
        $salaryArchive->delete();

        return redirect()
            ->route('salary_archives.index')
            ->with(
                'success',
                'Arsip gaji dan seluruh data di dalamnya berhasil dihapus.'
            );
    }


    public function editChecklog(
        SalaryArchive $salaryArchive,
        Checklog $checklog
    ) {
        // Pastikan checklog memang milik arsip tersebut
        if ($checklog->salary_archive_id != $salaryArchive->id) {
            abort(404);
        }

        $checklog->load('employee');

        return view(
            'salary_archives.edit_checklog',
            compact('salaryArchive', 'checklog')
        );
    }


    public function updateChecklog(
        Request $request,
        SalaryArchive $salaryArchive,
        Checklog $checklog
    ) {
        // Pastikan checklog memang milik arsip tersebut
        if ($checklog->salary_archive_id != $salaryArchive->id) {
            abort(404);
        }

        $request->validate([
            'jam_istirahat' => [
                'required',
                'regex:/^\d+(\.[0-5][0-9])?$/'
            ],
        ], [
            'jam_istirahat.regex' =>
                'Format istirahat harus seperti 0.00, 0.30, 1.00, atau 1.30.'
        ]);


        $jamIstirahat = (string) $request->jam_istirahat;


        // Ubah format 1.30 menjadi 1 jam 30 menit
        if (str_contains($jamIstirahat, '.')) {

            [$jam, $menit] = explode(
                '.',
                $jamIstirahat
            );

            $jam = (int) $jam;

            $menit = (int) str_pad(
                $menit,
                2,
                '0'
            );

        } else {

            $jam = (int) $jamIstirahat;

            $menit = 0;
        }


        $breakMinutes = ($jam * 60) + $menit;


        $totalJamKerja = 0;

        $gaji = 0;


        // Hitung ulang berdasarkan scan masuk dan scan pulang
        if (
            $checklog->scan_masuk &&
            $checklog->scan_pulang
        ) {

            $masuk = \Carbon\Carbon::createFromFormat(
                'H:i:s',
                $checklog->scan_masuk
            );

            $pulang = \Carbon\Carbon::createFromFormat(
                'H:i:s',
                $checklog->scan_pulang
            );


            // Jika pulang melewati tengah malam
            if ($pulang->lessThanOrEqualTo($masuk)) {
                $pulang->addDay();
            }


            $totalMinutes = $masuk->diffInMinutes(
                $pulang
            );


            // Kurangi waktu istirahat
            $workMinutes = max(
                0,
                $totalMinutes - $breakMinutes
            );


            $totalJamKerja = $workMinutes / 60;


            // Rp18.000 per jam
            $gaji = $workMinutes * (18000 / 60);
        }


        // Simpan perubahan
        $checklog->update([
            'jam_istirahat' => $jamIstirahat,
            'total_jam_kerja' => $totalJamKerja,
            'hourly_rate' => 18000,
            'gaji' => $gaji,
        ]);


        // Hitung ulang total arsip
        $salaryArchive->update([
            'total_salary' => $salaryArchive
                ->checklogs()
                ->sum('gaji'),

            'total_checklogs' => $salaryArchive
                ->checklogs()
                ->count(),

            'total_employees' => $salaryArchive
                ->checklogs()
                ->distinct('borongan_employee_id')
                ->count('borongan_employee_id'),
        ]);


        return redirect()
            ->route(
                'salary_archives.show',
                $salaryArchive
            )
            ->with(
                'success',
                'Data checklog berhasil diperbarui.'
            );
    }


    // Hapus satu checklog dari arsip
    public function destroyChecklog(
        SalaryArchive $salaryArchive,
        Checklog $checklog
    ) {
        // Pastikan checklog memang milik arsip tersebut
        if ($checklog->salary_archive_id != $salaryArchive->id) {
            abort(404);
        }


        // Hapus checklog
        $checklog->delete();


        // Hitung ulang total arsip
        $salaryArchive->update([
            'total_salary' => $salaryArchive
                ->checklogs()
                ->sum('gaji'),

            'total_checklogs' => $salaryArchive
                ->checklogs()
                ->count(),

            'total_employees' => $salaryArchive
                ->checklogs()
                ->distinct('borongan_employee_id')
                ->count('borongan_employee_id'),
        ]);


        return redirect()
            ->route(
                'salary_archives.show',
                $salaryArchive
            )
            ->with(
                'success',
                'Data checklog berhasil dihapus.'
            );
    }
}