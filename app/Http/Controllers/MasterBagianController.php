<?php
namespace App\Http\Controllers;

use App\Models\MasterBagian;
use App\Models\DailyWorkSchedule;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MasterBagianController extends Controller
{
    private const BAGIAN_REFERENCE_TABLES = [
        'employees',
        'borongan_employees',
        'daily_work_schedules',
        'contracts',
        'salary_archive_details',
    ];

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

        DB::transaction(function () use ($masterBagian, $validated) {
            $namaLama = $masterBagian->nama;
            $namaBaru = $validated['nama'];

            $masterBagian->update($validated);

            foreach (self::BAGIAN_REFERENCE_TABLES as $table) {
                DB::table($table)
                    ->where('bagian', $namaLama)
                    ->update([
                        'bagian' => $namaBaru,
                        'updated_at' => now(),
                    ]);
            }
        });

        return back()->with('success', 'Bagian berhasil diperbarui.');
    }

    public function destroy(MasterBagian $masterBagian)
    {
        $isUsed = false;

        foreach (self::BAGIAN_REFERENCE_TABLES as $table) {
            if (DB::table($table)->where('bagian', $masterBagian->nama)->exists()) {
                $isUsed = true;
                break;
            }
        }

        if ($isUsed) {
            return back()->withErrors([
                'delete' => 'Bagian masih digunakan pada data karyawan atau jadwal kerja harian, sehingga tidak dapat dihapus.',
            ]);
        }

        $masterBagian->delete();

        return back()->with('success', 'Bagian berhasil dihapus.');
    }
    
}
