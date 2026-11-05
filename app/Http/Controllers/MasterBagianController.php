<?php
namespace App\Http\Controllers;

use App\Models\MasterBagian;
use App\Models\DailyWorkSchedule;
use App\Models\Employee;
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

    public function destroy(MasterBagian $masterBagian)
    {
        $isUsed = Employee::where('bagian', $masterBagian->nama)->exists()
            || DailyWorkSchedule::where('bagian', $masterBagian->nama)->exists();

        if ($isUsed) {
            return back()->withErrors([
                'delete' => 'Bagian masih digunakan pada data karyawan atau jadwal kerja harian, sehingga tidak dapat dihapus.',
            ]);
        }

        $masterBagian->delete();

        return back()->with('success', 'Bagian berhasil dihapus.');
    }
    
}
