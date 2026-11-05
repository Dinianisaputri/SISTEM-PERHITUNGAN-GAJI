<?php
namespace App\Http\Controllers;

use App\Models\MasterJabatan;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterJabatanController extends Controller
{
    public function index()
    {
        $data = MasterJabatan::orderBy('nama')->paginate(20);

        return view('master_data.jabatans.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:master_jabatans,nama'],
        ]);

        MasterJabatan::create($validated);

        return back()->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, MasterJabatan $masterJabatan)
    {
        $validated = $request->validate([
            'nama' => [
                'required', 'string', 'max:255',
                Rule::unique('master_jabatans', 'nama')->ignore($masterJabatan->id),
            ],
        ]);

        $masterJabatan->update($validated);

        return back()->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(MasterJabatan $masterJabatan)
    {
        if (Employee::where('jabatan', $masterJabatan->nama)->exists()) {
            return back()->withErrors([
                'delete' => 'Jabatan masih digunakan pada data karyawan, sehingga tidak dapat dihapus.',
            ]);
        }

        $masterJabatan->delete();

        return back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
