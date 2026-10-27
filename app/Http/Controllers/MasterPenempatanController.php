<?php
namespace App\Http\Controllers;

use App\Models\MasterPenempatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterPenempatanController extends Controller
{
    public function index()
    {
        $data = MasterPenempatan::orderBy('nama')->paginate(20);

        return view('master_data.penempatans.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:master_penempatans,nama'],
        ]);

        MasterPenempatan::create($validated);

        return back()->with('success', 'Penempatan berhasil ditambahkan.');
    }

    public function update(Request $request, MasterPenempatan $masterPenempatan)
    {
        $validated = $request->validate([
            'nama' => [
                'required', 'string', 'max:255',
                Rule::unique('master_penempatans', 'nama')->ignore($masterPenempatan->id),
            ],
        ]);

        $masterPenempatan->update($validated);

        return back()->with('success', 'Penempatan berhasil diperbarui.');
    }

    public function toggle(MasterPenempatan $masterPenempatan)
    {
        $masterPenempatan->update([
            'is_active' => !$masterPenempatan->is_active,
        ]);

        return back()->with('success', 'Status penempatan berhasil diubah.');
    }
}
