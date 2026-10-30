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
                $query->where('bpjs_tk_terdaftar', true)
                    ->orWhere('bpjs_kesehatan_terdaftar', true);
            })
            ->when(
                in_array($programBpjsTk, ['2 Program', 'Full Program'], true),
                function ($query) use ($programBpjsTk) {
                    $query->where('bpjs_tk_terdaftar', true)
                        ->where('program_bpjs_tk', $programBpjsTk);
                }
            )
            ->when(
                in_array($kelasBpjsKesehatan, ['Kelas 1', 'Kelas 2'], true),
                function ($query) use ($kelasBpjsKesehatan) {
                    $query->where('bpjs_kesehatan_terdaftar', true)
                        ->where('kelas_bpjs_kesehatan', $kelasBpjsKesehatan);
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
