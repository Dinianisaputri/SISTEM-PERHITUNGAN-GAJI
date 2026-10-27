<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarningLetter;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
class WarningLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function print(WarningLetter $warningLetter)
{
    $warningLetter->load('employee');

    $pdf = Pdf::loadView(
        'warning-letters.pdf',
        compact('warningLetter')
    )->setPaper('a4', 'portrait');

    return $pdf->stream(
        'Surat-Peringatan-' . $warningLetter->id . '.pdf'
    );
}

public function index()
{
    $warningLetters = WarningLetter::with('employee')
        ->latest('tanggal_surat')
        ->paginate(10);

    return view('warning-letters.index', compact('warningLetters'));
}

public function create()
{
    $employees = Employee::where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('warning-letters.create', compact('employees'));
}

    /**
     * Store a newly created resource in storage.
     */


public function store(Request $request)
{
    $validated = $request->validate([
        'employee_id' => ['required', 'exists:employees,id'],
        'nomor_surat' => [
            'required',
            'string',
            'max:255',
            'unique:warning_letters,nomor_surat',
        ],
        'tanggal_surat' => ['required', 'date'],
        'tingkat_sp' => ['required', 'integer', 'in:1,2,3'],
        'alasan_pelanggaran' => ['required', 'string'],
        'tanggal_mulai_berlaku' => ['required', 'date'],
        'tanggal_akhir_berlaku' => ['nullable', 'date'],
        'keterangan' => ['nullable', 'string'],
    ]);

    $tanggalPemeriksaan = Carbon::parse(
        $validated['tanggal_surat']
    )->toDateString();

    // Cari SP terakhir yang masih berlaku pada tanggal surat.
    $spTerakhir = WarningLetter::where(
        'employee_id',
        $validated['employee_id']
    )
        ->whereDate('tanggal_mulai_berlaku', '<=', $tanggalPemeriksaan)
        ->whereDate('tanggal_akhir_berlaku', '>=', $tanggalPemeriksaan)
        ->orderByDesc('tanggal_mulai_berlaku')
        ->orderByDesc('id')
        ->first();

    // Jika SP III masih berlaku, HRD perlu meninjau kasus secara manual.
    if ($spTerakhir && (int) $spTerakhir->tingkat_sp === 3) {
        return back()
            ->withErrors([
                'tingkat_sp' =>
                    'Karyawan masih memiliki SP III yang berlaku. '
                    . 'Kasus perlu ditinjau HRD sebelum surat berikutnya diterbitkan.',
            ])
            ->withInput();
    }

    // Tentukan tingkat SP yang diperbolehkan berdasarkan riwayat aktif.
    $tingkatSeharusnya = $spTerakhir
        ? (int) $spTerakhir->tingkat_sp + 1
        : 1;

    // HRD harus memilih dan mengonfirmasi tingkat SP yang sesuai.
    if ((int) $validated['tingkat_sp'] !== $tingkatSeharusnya) {
        return back()
            ->withErrors([
                'tingkat_sp' =>
                    'Berdasarkan riwayat dan masa berlaku SP, '
                    . 'tingkat yang seharusnya diterbitkan adalah SP '
                    . $tingkatSeharusnya . '.',
            ])
            ->withInput();
    }

    // Tanggal akhir otomatis enam bulan setelah tanggal mulai.
    $tanggalMulai = Carbon::parse(
        $validated['tanggal_mulai_berlaku']
    );

    $validated['tanggal_akhir_berlaku'] =
        $tanggalMulai->copy()->addMonthsNoOverflow(6)->toDateString();

    $validated['status'] = 'aktif';

    WarningLetter::create($validated);

    return redirect()
        ->route('warning-letters.index')
        ->with('success', 'Surat Peringatan berhasil disimpan.');
}

     
public function show(WarningLetter $warningLetter)
{
    $warningLetter->load('employee');

    return view('warning-letters.show', compact('warningLetter'));
}
    /**
     * Show the form for editing the specified resource.
     */

public function edit(WarningLetter $warningLetter)
{
    $employees = Employee::where('is_active', true)
        ->orderBy('name')
        ->get();

    // Tetap tampilkan karyawan surat ini meskipun sudah tidak aktif.
    if (!$employees->contains('id', $warningLetter->employee_id)) {
        $employees->push($warningLetter->employee);
    }

    return view('warning-letters.edit', compact('warningLetter', 'employees'));
}

public function update(Request $request, WarningLetter $warningLetter)
{
    $validated = $request->validate([
        'employee_id' => ['required', 'exists:employees,id'],
        'nomor_surat' => [
            'required',
            'string',
            'max:255',
            'unique:warning_letters,nomor_surat,' . $warningLetter->id,
        ],
        'tanggal_surat' => ['required', 'date'],
        'tingkat_sp' => ['required', 'integer', 'in:1,2,3'],
        'alasan_pelanggaran' => ['required', 'string'],
        'tanggal_mulai_berlaku' => ['required', 'date'],
        'tanggal_akhir_berlaku' => [
            'nullable',
            'date',
            'after_or_equal:tanggal_mulai_berlaku',
        ],
        'keterangan' => ['nullable', 'string'],
    ]);

    $warningLetter->update($validated);

    return redirect()
        ->route('warning-letters.show', $warningLetter)
        ->with('success', 'Surat Peringatan berhasil diperbarui.');
}
 
public function destroy(WarningLetter $warningLetter)
{
    $warningLetter->delete();

    return redirect()
        ->route('warning-letters.index')
        ->with('success', 'Surat Peringatan berhasil dihapus.');
}
}
