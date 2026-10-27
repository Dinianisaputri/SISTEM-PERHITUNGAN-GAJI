<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContractController extends Controller
{
   public function index()
{
    $contracts = Contract::with('employee')
        ->orderBy('tanggal_mulai', 'asc')
        ->orderBy('id', 'asc')
        ->paginate(25);

    return view('contracts.index', compact('contracts'));
}
    public function create()
    {
        $employees = Employee::orderBy('name')->get();

        return view('contracts.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
            ],
            'nomor_kontrak' => [
                'required',
                'string',
                'max:255',
                'unique:contracts,nomor_kontrak',
            ],
            'jenis_kontrak' => [
                'required',
                Rule::in([
                    'PKWT BO Jam FF',
                    'PKWT BO Jam FM',
                    'PKWT BO Jam SR',
                    'PKWT BO Jam VA',
                    'PKWT FF',
                    'PKWT FM',
                    'PKWT Harian',
                    'PKWT Satpam',
                ]),
            ],
            'bagian' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_berakhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'status' => [
                'required',
                Rule::in(['aktif', 'berakhir', 'dibatalkan']),
            ],
            'keterangan' => ['nullable', 'string'],
        ]);

        Contract::create($validated);

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Surat kontrak berhasil ditambahkan.');
    }

    public function show(Contract $contract)
    {
        $contract->load('employee');

        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $employees = Employee::orderBy('name')->get();

        return view('contracts.edit', compact('contract', 'employees'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
            ],
            'nomor_kontrak' => [
                'required',
                'string',
                'max:255',
                Rule::unique('contracts', 'nomor_kontrak')
                    ->ignore($contract->id),
            ],
            'jenis_kontrak' => [
                'required',
                Rule::in([
                    'PKWT BO Jam FF',
                    'PKWT BO Jam FM',
                    'PKWT BO Jam SR',
                    'PKWT BO Jam VA',
                    'PKWT FF',
                    'PKWT FM',
                    'PKWT Harian',
                    'PKWT Satpam',
                ]),
            ],
            'bagian' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_berakhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'status' => [
                'required',
                Rule::in(['aktif', 'berakhir', 'dibatalkan']),
            ],
            'keterangan' => ['nullable', 'string'],
        ]);

        $contract->update($validated);

        return redirect()
            ->route('contracts.show', $contract)
            ->with('success', 'Surat kontrak berhasil diperbarui.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Data surat kontrak berhasil dihapus.');
    }
    

public function print(\App\Models\Contract $contract)
{
    $contract->load('employee');

    $templateView = match ($contract->jenis_kontrak) {
        'PKWT BO Jam FF' => 'contracts.pdf.bo-jam-ff',
         'PKWT BO Jam FM' => 'contracts.pdf.bo-jam-fm',
        'PKWT BO Jam SR' => 'contracts.pdf.bo-jam-sr',
        'PKWT BO Jam VA' => 'contracts.pdf.bo-jam-va',
        'PKWT FF' => 'contracts.pdf.pkwt-ff',
        'PKWT FM' => 'contracts.pdf.pkwt-fm',
        'PKWT Harian' => 'contracts.pdf.pkwt-harian',
        'PKWT Satpam' => 'contracts.pdf.pkwt-satpam',
        default => null,
    };

    if (!$templateView) {
        return redirect()
            ->route('contracts.index')
            ->with(
                'error',
                'Template PDF untuk jenis kontrak ini belum tersedia.'
            );
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        $templateView,
        compact('contract')
    );

    $pdf->setPaper('A4', 'portrait');

    $namaKaryawan = $contract->employee->name ?? 'Karyawan';

$namaKaryawan = preg_replace(
    '/[^A-Za-z0-9_-]/',
    '_',
    trim($namaKaryawan)
);

return $pdf->download(
    'Kontrak-' . $namaKaryawan . '.pdf'
);
}
}