<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    private function master(string $jenis): array
    {
        $daftar = [
            'bagian' => [
                'table' => 'master_bagians',
                'judul' => 'Master Bagian',
            ],
            'jabatan' => [
                'table' => 'master_jabatans',
                'judul' => 'Master Jabatan',
            ],
            'penempatan' => [
                'table' => 'master_penempatans',
                'judul' => 'Master Penempatan',
            ],
            'kategori-regulasi' => [
                'table' => 'master_kategori_regulations',
                'judul' => 'Kategori Regulasi',
            ],
        ];

        abort_unless(isset($daftar[$jenis]), 404);

        return $daftar[$jenis];
    }

    public function index(string $jenis)
    {
        $master = $this->master($jenis);

        $data = DB::table($master['table'])
            ->orderBy('nama')
            ->get();

        return view('master-data.index', [
            'jenis' => $jenis,
            'judul' => $master['judul'],
            'data' => $data,
        ]);
    }

    public function store(Request $request, string $jenis)
    {
        $master = $this->master($jenis);
        $table = $master['table'];

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique($table, 'nama'),
            ],
        ]);

        DB::table($table)->insert([
            'nama' => trim($validated['nama']),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('master-data.index', $jenis)
            ->with('success', 'Data master berhasil ditambahkan.');
    }

    public function update(
        Request $request,
        string $jenis,
        int $id
    ) {
        $master = $this->master($jenis);
        $table = $master['table'];

        $item = DB::table($table)->where('id', $id)->first();

        abort_unless($item, 404);

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique($table, 'nama')->ignore($id),
            ],
        ]);

        DB::table($table)
            ->where('id', $id)
            ->update([
                'nama' => trim($validated['nama']),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('master-data.index', $jenis)
            ->with('success', 'Data master berhasil diperbarui.');
    }

    public function toggle(string $jenis, int $id)
    {
        $master = $this->master($jenis);
        $table = $master['table'];

        $item = DB::table($table)->where('id', $id)->first();

        abort_unless($item, 404);

        DB::table($table)
            ->where('id', $id)
            ->update([
                'is_active' => !$item->is_active,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('master-data.index', $jenis)
            ->with(
                'success',
                $item->is_active
                    ? 'Master berhasil dinonaktifkan.'
                    : 'Master berhasil diaktifkan kembali.'
            );
    }
}
