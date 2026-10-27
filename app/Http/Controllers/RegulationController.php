<?php

namespace App\Http\Controllers;

use App\Models\Regulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegulationController extends Controller
{
    /**
     * Daftar kategori regulasi
     */
  private function kategoriList()
{
    return DB::table('master_kategori_regulations')
        ->where('is_active', true)
        ->orderBy('nama')
        ->pluck('nama')
        ->toArray();
}

    /**
     * Dashboard Regulasi
     */
    public function dashboard()
    {
        $regulations = Regulation::query()
            ->orderBy('tanggal_berakhir', 'asc')
            ->get();

        // Total regulasi
        $totalRegulasi = $regulations->count();

        // Status pemenuhan
        $terpenuhi = $regulations
            ->where('status', 'Terpenuhi')
            ->count();

        $tidakTerpenuhi = $regulations
            ->where('status', 'Tidak Terpenuhi')
            ->count();

        // Status masa berlaku
        $expired = 0;
        $segeraBerakhir = 0;
        $akanBerakhir = 0;
        $masihAktif = 0;

        foreach ($regulations as $regulation) {

            if (!$regulation->tanggal_berakhir) {
                continue;
            }

            $hariTersisa = now()->startOfDay()->diffInDays(
                $regulation->tanggal_berakhir->startOfDay(),
                false
            );

            if ($hariTersisa < 0) {

                $expired++;

            } elseif ($hariTersisa <= 30) {

                $segeraBerakhir++;

            } elseif ($hariTersisa <= 90) {

                $akanBerakhir++;

            } else {

                $masihAktif++;

            }
        }


        // Regulasi yang akan berakhir dalam 90 hari
        $regulasiAkanBerakhir = $regulations
            ->filter(function ($regulation) {

                if (!$regulation->tanggal_berakhir) {
                    return false;
                }

                $hariTersisa = now()->startOfDay()->diffInDays(
                    $regulation->tanggal_berakhir->startOfDay(),
                    false
                );

                return $hariTersisa >= 0 && $hariTersisa <= 90;
            })
            ->take(10);


        return view('regulations.dashboard', compact(
            'totalRegulasi',
            'terpenuhi',
            'tidakTerpenuhi',
            'expired',
            'segeraBerakhir',
            'akanBerakhir',
            'masihAktif',
            'regulasiAkanBerakhir'
        ));
    }


    /**
     * Menampilkan semua regulasi
     */
    public function index(Request $request)
    {
        $query = Regulation::query();

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'jenis_perijinan',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'regulasi',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'nomor_surat',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'nomor_ijin',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'bagian_terkait',
                    'like',
                    "%{$search}%"
                );

            });
        }

        $regulations = $query
            ->orderBy('tanggal_berakhir', 'asc')
            ->paginate(15)
            ->withQueryString();

        $kategoriList = $this->kategoriList();

        return view(
            'regulations.index',
            compact(
                'regulations',
                'kategoriList'
            )
        );
    }


    /**
     * Form tambah regulasi
     */
    public function create()
    {
        $kategoriList = $this->kategoriList();

        return view(
            'regulations.create',
            compact('kategoriList')
        );
    }


    /**
     * Simpan regulasi baru
     */
    public function store(Request $request)
    {
                    $validated = $request->validate([

                    'kategori' => [
                'required',
                'string',
                'max:255',
                Rule::in($this->kategoriList()),
            ],
            'jenis_perijinan' => 'required|string|max:255',

            'regulasi' => 'nullable|string',

            'nomor_surat' => 'nullable|string|max:255',

            'nomor_ijin' => 'nullable|string|max:255',

            'tanggal_mulai' => 'nullable|date',

            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',

            'masa_berlaku' => 'nullable|string|max:255',

            'bagian_terkait' => 'nullable|string|max:255',

            'status' => 'required|in:Terpenuhi,Tidak Terpenuhi',

            'keterangan' => 'nullable|string',

            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

        ]);


        // Upload dokumen
        if ($request->hasFile('dokumen')) {

            $validated['dokumen'] = $request
                ->file('dokumen')
                ->store('regulations', 'public');
        }


        Regulation::create($validated);


        return redirect()
            ->route('regulations.index')
            ->with(
                'success',
                'Data regulasi berhasil ditambahkan.'
            );
    }


    /**
     * Detail regulasi
     */
    public function show(Regulation $regulation)
    {
        return view(
            'regulations.show',
            compact('regulation')
        );
    }


    /**
     * Form edit regulasi
     */
    public function edit(Regulation $regulation)
    {
        $kategoriList = $this->kategoriList();

        return view(
            'regulations.edit',
            compact(
                'regulation',
                'kategoriList'
            )
        );
    }


    /**
     * Update regulasi
     */
    public function update(
        Request $request,
        Regulation $regulation
    ) {
                        $validated = $request->validate([

                    'kategori' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::in(array_values(array_unique(array_merge(
                        $this->kategoriList(),
                        [$regulation->kategori]
                    )))),
                ],

            'jenis_perijinan' => 'required|string|max:255',

            'regulasi' => 'nullable|string',

            'nomor_surat' => 'nullable|string|max:255',

            'nomor_ijin' => 'nullable|string|max:255',

            'tanggal_mulai' => 'nullable|date',

            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',

            'masa_berlaku' => 'nullable|string|max:255',

            'bagian_terkait' => 'nullable|string|max:255',

            'status' => 'required|in:Terpenuhi,Tidak Terpenuhi',

            'keterangan' => 'nullable|string',

            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

        ]);


        // Jika upload dokumen baru
        if ($request->hasFile('dokumen')) {

            // Hapus dokumen lama
            if ($regulation->dokumen) {

                Storage::disk('public')
                    ->delete($regulation->dokumen);
            }


            // Simpan dokumen baru
            $validated['dokumen'] = $request
                ->file('dokumen')
                ->store('regulations', 'public');
        }


        $regulation->update($validated);


        return redirect()
            ->route('regulations.index')
            ->with(
                'success',
                'Data regulasi berhasil diperbarui.'
            );
    }


    /**
     * Hapus regulasi
     */
    public function destroy(Regulation $regulation)
    {
        // Hapus dokumen jika ada
        if ($regulation->dokumen) {

            Storage::disk('public')
                ->delete($regulation->dokumen);
        }


        $regulation->delete();


        return redirect()
            ->route('regulations.index')
            ->with(
                'success',
                'Data regulasi berhasil dihapus.'
            );
    }


    /**
     * Download dokumen regulasi
     */
    public function download(Regulation $regulation)
    {
        if (!$regulation->dokumen) {

            return back()->with(
                'error',
                'Dokumen tidak tersedia.'
            );
        }


        if (!Storage::disk('public')->exists($regulation->dokumen)) {

            return back()->with(
                'error',
                'File dokumen tidak ditemukan.'
            );
        }


        return response()->download(
            Storage::disk('public')->path($regulation->dokumen),
            basename($regulation->dokumen)
        );
    }
}
