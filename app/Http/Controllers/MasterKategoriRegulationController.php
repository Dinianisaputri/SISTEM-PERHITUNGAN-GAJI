<?php
namespace App\Http\Controllers;

use App\Models\MasterKategoriRegulation;
use App\Models\Regulation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterKategoriRegulationController extends Controller
{
    public function index()
    {
        $data = MasterKategoriRegulation::orderBy('nama')->paginate(20);

        return view('master_data.kategori_regulations.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required', 'string', 'max:255',
                'unique:master_kategori_regulations,nama',
            ],
        ]);

        MasterKategoriRegulation::create($validated);

        return back()->with('success', 'Kategori regulasi berhasil ditambahkan.');
    }

    public function update(
        Request $request,
        MasterKategoriRegulation $masterKategoriRegulation
    ) {
        $validated = $request->validate([
            'nama' => [
                'required', 'string', 'max:255',
                Rule::unique('master_kategori_regulations', 'nama')
                    ->ignore($masterKategoriRegulation->id),
            ],
        ]);

        $masterKategoriRegulation->update($validated);

        return back()->with('success', 'Kategori regulasi berhasil diperbarui.');
    }

    public function destroy(MasterKategoriRegulation $masterKategoriRegulation)
    {
        if (Regulation::where('kategori', $masterKategoriRegulation->nama)->exists()) {
            return back()->withErrors([
                'delete' => 'Kategori masih digunakan pada data regulasi, sehingga tidak dapat dihapus.',
            ]);
        }

        $masterKategoriRegulation->delete();

        return back()->with('success', 'Kategori regulasi berhasil dihapus.');
    }
}
