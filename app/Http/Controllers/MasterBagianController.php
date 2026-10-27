<?php
namespace App\Http\Controllers;

use App\Models\MasterBagian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterBagianController extends Controller
{
    public function index()
    {
        $data = MasterBagian::orderBy('nama')->paginate(20);

        return view('master_data.bagians.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:master_bagians,nama'],
        ]);

        MasterBagian::create($validated);

        return back()->with('success', 'Bagian berhasil ditambahkan.');
    }

    public function update(Request $request, MasterBagian $masterBagian)
    {
        $validated = $request->validate([
            'nama' => [
                'required', 'string', 'max:255',
                Rule::unique('master_bagians', 'nama')->ignore($masterBagian->id),
            ],
        ]);

        $masterBagian->update($validated);

        return back()->with('success', 'Bagian berhasil diperbarui.');
    }

    public function toggle(MasterBagian $masterBagian)
    {
        $masterBagian->update([
            'is_active' => !$masterBagian->is_active,
        ]);

        return back()->with('success', 'Status bagian berhasil diubah.');
    }
    
}
