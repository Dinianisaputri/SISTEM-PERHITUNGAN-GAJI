<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->salinNilaiUnik(
            'employees',
            'bagian',
            'master_bagians'
        );

        $this->salinNilaiUnik(
            'employees',
            'jabatan',
            'master_jabatans'
        );

        $this->salinNilaiUnik(
            'employees',
            'penempatan',
            'master_penempatans'
        );

        $this->salinNilaiUnik(
            'regulations',
            'kategori',
            'master_kategori_regulations'
        );

        $kategoriLama = [
            'KORPORASI DAN TATA KELOLA',
            'IZIN KEMENTERIAN KELAUTAN DAN PERIKANAN',
            'REGISTRASI NEGARA MITRA & FDA',
            'IJIN EDAR PANGAN OLAHAN & SEGAR',
            'TENAGA KERJA ASING DAN HUBUNGAN INDUSTRIAL',
            'KESEHATAN DAN KESELAMATAN KERJA (K3)',
            'INVENTARIS DAN KENDARAAN',
        ];

        foreach ($kategoriLama as $nama) {
            $sudahAda = DB::table('master_kategori_regulations')
                ->where('nama', $nama)
                ->exists();

            if (!$sudahAda) {
                DB::table('master_kategori_regulations')->insert([
                    'nama' => $nama,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function salinNilaiUnik(
        string $tabelSumber,
        string $kolomSumber,
        string $tabelTujuan
    ): void {
        if (
            !Schema::hasTable($tabelSumber) ||
            !Schema::hasTable($tabelTujuan)
        ) {
            throw new RuntimeException(
                "Tabel {$tabelSumber} atau {$tabelTujuan} belum tersedia."
            );
        }

        if (
            !Schema::hasColumn($tabelTujuan, 'nama') ||
            !Schema::hasColumn($tabelTujuan, 'is_active')
        ) {
            throw new RuntimeException(
                "Kolom nama/is_active belum tersedia di {$tabelTujuan}. Jalankan php artisan migrate terlebih dahulu."
            );
        }

        $nilaiUnik = DB::table($tabelSumber)
            ->whereNotNull($kolomSumber)
            ->where($kolomSumber, '<>', '')
            ->distinct()
            ->pluck($kolomSumber);

        foreach ($nilaiUnik as $nilai) {
            $nama = trim($nilai);

            if ($nama === '') {
                continue;
            }

            $sudahAda = DB::table($tabelTujuan)
                ->where('nama', $nama)
                ->exists();

            if (!$sudahAda) {
                DB::table($tabelTujuan)->insert([
                    'nama' => $nama,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}