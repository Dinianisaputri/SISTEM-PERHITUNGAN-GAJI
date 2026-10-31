<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class MasterBpjsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $programBpjsTk = (string) $request->query('program_bpjs_tk', '');
        $kelasBpjsKesehatan = (string) $request->query('kelas_bpjs_kesehatan', '');

        $employees = Employee::query()
            ->where(function ($query) {
                $query->where(function ($tk) {
                    $tk->whereNotNull('program_bpjs_tk')
                        ->whereRaw("TRIM(program_bpjs_tk) <> ''")
                        ->whereNotNull('no_bpjs_tk')
                        ->whereRaw("TRIM(no_bpjs_tk) <> ''");
                })->orWhere(function ($kesehatan) {
                    $kesehatan->whereNotNull('kelas_bpjs_kesehatan')
                        ->whereRaw("TRIM(kelas_bpjs_kesehatan) <> ''")
                        ->whereNotNull('no_bpjs_kesehatan')
                        ->whereRaw("TRIM(no_bpjs_kesehatan) <> ''");
                });
            })
            ->when(
                in_array($programBpjsTk, ['2 Program', 'Full Program'], true),
                function ($query) use ($programBpjsTk) {
                    $query->where('program_bpjs_tk', $programBpjsTk)
                        ->whereNotNull('no_bpjs_tk')
                        ->whereRaw("TRIM(no_bpjs_tk) <> ''");
                }
            )
            ->when(
                in_array($kelasBpjsKesehatan, ['Kelas 1', 'Kelas 2'], true),
                function ($query) use ($kelasBpjsKesehatan) {
                    $query->where('kelas_bpjs_kesehatan', $kelasBpjsKesehatan)
                        ->whereNotNull('no_bpjs_kesehatan')
                        ->whereRaw("TRIM(no_bpjs_kesehatan) <> ''");
                }
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('no_induk_karyawan', 'like', "%{$search}%")
                        ->orWhere('fingerprint_pin', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('master-bpjs.index', compact(
            'employees',
            'search',
            'programBpjsTk',
            'kelasBpjsKesehatan'
        ));
    }

    public function updateTarif(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'tarif_bpjs' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999999999.99',
            ],
        ]);

        $employee->tarif_bpjs =
            $validated['tarif_bpjs'] ?? null;

        $employee->save();

        return redirect()
            ->back()
            ->with(
                'success',
                'Tarif BPJS ' . $employee->name . ' berhasil disimpan.'
            );
    }
}
