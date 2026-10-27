<?php

namespace App\Http\Controllers;

use App\Models\WarningNotice;
use App\Models\Employee;
use Illuminate\Http\Request;

class WarningNoticeController extends Controller
{
    public function index()
    {
        $warningNotices = WarningNotice::with('employee')
            ->latest('tanggal_surat')
            ->paginate(10);

        return view('warning-notices.index', compact('warningNotices'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();

        return view('warning-notices.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:warning_notices,nomor_surat'],
            'tanggal_surat' => ['required', 'date'],
            'alasan_pelanggaran' => ['required', 'string'],
            'tanggal_mulai_berlaku' => ['required', 'date'],
            'tanggal_akhir_berlaku' => [
                    'required',
                    'date',
                    'after_or_equal:tanggal_mulai_berlaku',
                ],
            'nama_kepala_unit' => ['required', 'string', 'max:255'],
        ]);

        WarningNotice::create($validated);

        return redirect()
            ->route('warning-notices.index')
            ->with('success', 'Surat Teguran berhasil disimpan.');
    }

    public function show(WarningNotice $warningNotice)
    {
        $warningNotice->load('employee');

        return view('warning-notices.show', compact('warningNotice'));
    }

    public function edit(WarningNotice $warningNotice)
    {
        $employees = Employee::orderBy('name')->get();

        return view(
            'warning-notices.edit',
            compact('warningNotice', 'employees')
        );
    }

    public function update(Request $request, WarningNotice $warningNotice)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'nomor_surat' => [
                'required',
                'string',
                'max:255',
                'unique:warning_notices,nomor_surat,' . $warningNotice->id,
            ],
            'tanggal_surat' => ['required', 'date'],
            'alasan_pelanggaran' => ['required', 'string'],
            'tanggal_mulai_berlaku' => ['required', 'date'],
            'tanggal_akhir_berlaku' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai_berlaku',
            ],
            'nama_kepala_unit' => ['required', 'string', 'max:255'],
        ]);

        $warningNotice->update($validated);

        return redirect()
            ->route('warning-notices.show', $warningNotice)
            ->with('success', 'Surat Teguran berhasil diperbarui.');
    }

    public function destroy(WarningNotice $warningNotice)
    {
        $warningNotice->delete();

        return redirect()
            ->route('warning-notices.index')
            ->with('success', 'Surat Teguran berhasil dihapus.');
    }
    
public function print(WarningNotice $warningNotice)
{
    $warningNotice->load('employee');

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'warning-notices.pdf',
        compact('warningNotice')
    )->setPaper('a4', 'portrait');

    $namaFile = 'Surat-Teguran-' .
    str_replace(
        ['/', '\\'],
        '-',
        $warningNotice->nomor_surat
    ) . '.pdf';

return $pdf->stream($namaFile);
}

}
