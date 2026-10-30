<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ContractController extends Controller
{
    /**
     * Daftar surat kontrak.
     */
    public function index()
    {
        $contracts = Contract::with('employee')
            ->orderBy('tanggal_mulai', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(25);

        return view('contracts.index', compact('contracts'));
    }

    /**
     * Form tambah surat kontrak.
     */
    public function create()
    {
        $employees = Employee::orderBy('name')->get();

        return view('contracts.create', compact('employees'));
    }

    /**
     * Validasi data kontrak yang digunakan saat tambah dan edit.
     */
    private function contractRules(?Contract $contract = null): array
    {
        $nomorKontrakRule = Rule::unique('contracts', 'nomor_kontrak');

        if ($contract) {
            $nomorKontrakRule->ignore($contract->id);
        }

        return [
            'employee_id' => [
                'required',
                'integer',
                'exists:employees,id',
            ],

            'nomor_kontrak' => [
                'required',
                'string',
                'max:255',
                $nomorKontrakRule,
            ],

            // Nomor kontrak ke berapa diisi manual oleh pengguna.
            'kontrak_ke' => [
                'nullable',
                'integer',
                'min:1',
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

            'bagian' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_berakhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'status' => [
                'required',
                Rule::in([
                    'aktif',
                    'berakhir',
                    'dibatalkan',
                ]),
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],

            'file_surat' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ];
    }

    /**
     * Simpan surat kontrak baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->contractRules()
        );

        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request
                ->file('file_surat')
                ->store('contracts', 'public');
        }

        Contract::create($validated);

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Surat kontrak berhasil ditambahkan.');
    }

    /**
     * Detail surat kontrak.
     */
    public function show(Contract $contract)
    {
        $contract->load('employee');

        return view('contracts.show', compact('contract'));
    }

    /**
     * Form edit surat kontrak.
     */
    public function edit(Contract $contract)
    {
        $employees = Employee::orderBy('name')->get();

        return view(
            'contracts.edit',
            compact('contract', 'employees')
        );
    }

    /**
     * Perbarui surat kontrak.
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate(
            $this->contractRules($contract)
        );

        $fileLama = $contract->file_surat;
        $fileBaru = null;

        if ($request->hasFile('file_surat')) {
            $fileBaru = $request
                ->file('file_surat')
                ->store('contracts', 'public');

            $validated['file_surat'] = $fileBaru;
        }

        try {
            $contract->update($validated);
        } catch (\Throwable $exception) {
            // Jika pembaruan gagal, bersihkan file baru yang telanjur diunggah.
            if (
                $fileBaru &&
                Storage::disk('public')->exists($fileBaru)
            ) {
                Storage::disk('public')->delete($fileBaru);
            }

            throw $exception;
        }

        // Hapus file lama hanya setelah data berhasil diperbarui.
        if (
            $fileBaru &&
            $fileLama &&
            $fileLama !== $fileBaru &&
            Storage::disk('public')->exists($fileLama)
        ) {
            Storage::disk('public')->delete($fileLama);
        }

        return redirect()
            ->route('contracts.show', $contract)
            ->with('success', 'Surat kontrak berhasil diperbarui.');
    }

    /**
     * Hapus data surat kontrak.
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Data surat kontrak berhasil dihapus.');
    }

    /**
     * Cetak surat kontrak ke PDF.
     *
     * Template PDF yang sudah ada tetap digunakan.
     * Nomor urutan kontrak manual tidak ditambahkan ke PDF.
     */
    public function print(Contract $contract)
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

        $pdf = Pdf::loadView(
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
