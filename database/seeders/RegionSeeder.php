<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('data/wilayah-indonesia.csv.xlsx');

        if (!file_exists($file)) {
            $this->command->error('File Excel tidak ditemukan:');
            $this->command->error($file);
            return;
        }

        $this->command->info('Membaca file wilayah Indonesia...');
        $this->command->info($file);

        /*
        |--------------------------------------------------------------------------
        | BACA EXCEL
        |--------------------------------------------------------------------------
        */

        $reader = IOFactory::createReaderForFile($file);

        // Hanya membaca data, tanpa gambar/formula tambahan
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $this->command->info(
            "Total baris Excel: {$highestRow}"
        );

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $headerRow = $sheet->rangeToArray(
            "A1:{$highestColumn}1",
            null,
            true,
            true,
            true
        )[1];

        $headers = [];

        foreach ($headerRow as $column => $value) {
            $headers[$column] = strtoupper(trim((string) $value));
        }

        $requiredHeaders = [
            'KODE DESA',
            'NAMA KELURAHAN/DESA/DESA ADAT',
            'KODE KECAMATAN',
            'NAMA KECAMATAN',
            'KODE KABUPATEN',
            'NAMA KABUPATEN',
            'KODE PROVINSI',
            'NAMA PROVINSI',
        ];

        foreach ($requiredHeaders as $required) {
            if (!in_array($required, $headers, true)) {
                $this->command->error(
                    "Kolom '{$required}' tidak ditemukan."
                );

                $this->command->line(
                    'Header yang ditemukan: ' .
                    implode(', ', $headers)
                );

                $spreadsheet->disconnectWorksheets();

                return;
            }
        }

        $columns = array_flip($headers);

        $kodeDesaColumn = $columns['KODE DESA'];
        $namaDesaColumn = $columns['NAMA KELURAHAN/DESA/DESA ADAT'];

        $kodeKecamatanColumn = $columns['KODE KECAMATAN'];
        $namaKecamatanColumn = $columns['NAMA KECAMATAN'];

        $kodeKabupatenColumn = $columns['KODE KABUPATEN'];
        $namaKabupatenColumn = $columns['NAMA KABUPATEN'];

        $kodeProvinsiColumn = $columns['KODE PROVINSI'];
        $namaProvinsiColumn = $columns['NAMA PROVINSI'];

        /*
        |--------------------------------------------------------------------------
        | CACHE
        |--------------------------------------------------------------------------
        */

        $provinsiCache = [];
        $kabupatenCache = [];
        $kecamatanCache = [];

        $desaCount = 0;
        $kecamatanCount = 0;
        $kabupatenCount = 0;
        $provinsiCount = 0;

        /*
        |--------------------------------------------------------------------------
        | PROSES PER CHUNK
        |--------------------------------------------------------------------------
        */

        $chunkSize = 500;

        for (
            $startRow = 2;
            $startRow <= $highestRow;
            $startRow += $chunkSize
        ) {

            $endRow = min(
                $startRow + $chunkSize - 1,
                $highestRow
            );

            $rows = $sheet->rangeToArray(
                "A{$startRow}:{$highestColumn}{$endRow}",
                null,
                true,
                true,
                true
            );

            DB::transaction(function () use (
                $rows,
                $kodeDesaColumn,
                $namaDesaColumn,
                $kodeKecamatanColumn,
                $namaKecamatanColumn,
                $kodeKabupatenColumn,
                $namaKabupatenColumn,
                $kodeProvinsiColumn,
                $namaProvinsiColumn,
                &$provinsiCache,
                &$kabupatenCache,
                &$kecamatanCache,
                &$desaCount,
                &$kecamatanCount,
                &$kabupatenCount,
                &$provinsiCount
            ) {

                foreach ($rows as $row) {

                    $kodeProvinsi = trim(
                        (string) ($row[$kodeProvinsiColumn] ?? '')
                    );

                    $namaProvinsi = trim(
                        (string) ($row[$namaProvinsiColumn] ?? '')
                    );

                    $kodeKabupaten = trim(
                        (string) ($row[$kodeKabupatenColumn] ?? '')
                    );

                    $namaKabupaten = trim(
                        (string) ($row[$namaKabupatenColumn] ?? '')
                    );

                    $kodeKecamatan = trim(
                        (string) ($row[$kodeKecamatanColumn] ?? '')
                    );

                    $namaKecamatan = trim(
                        (string) ($row[$namaKecamatanColumn] ?? '')
                    );

                    $kodeDesa = trim(
                        (string) ($row[$kodeDesaColumn] ?? '')
                    );

                    $namaDesa = trim(
                        (string) ($row[$namaDesaColumn] ?? '')
                    );

                    if (
                        $kodeProvinsi === '' ||
                        $namaProvinsi === '' ||
                        $kodeKabupaten === '' ||
                        $namaKabupaten === '' ||
                        $kodeKecamatan === '' ||
                        $namaKecamatan === '' ||
                        $kodeDesa === '' ||
                        $namaDesa === ''
                    ) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | PROVINSI
                    |--------------------------------------------------------------------------
                    */

                    if (!isset($provinsiCache[$kodeProvinsi])) {

                        $provinsi = Region::updateOrCreate(
                            [
                                'kode' => $kodeProvinsi,
                            ],
                            [
                                'nama' => $namaProvinsi,
                                'level' => 'provinsi',
                                'parent_id' => null,
                            ]
                        );

                        $provinsiCache[$kodeProvinsi] = $provinsi->id;

                        $provinsiCount++;
                    }

                    $provinsiId =
                        $provinsiCache[$kodeProvinsi];

                    /*
                    |--------------------------------------------------------------------------
                    | KABUPATEN / KOTA
                    |--------------------------------------------------------------------------
                    */

                    if (!isset($kabupatenCache[$kodeKabupaten])) {

                        $kabupaten = Region::updateOrCreate(
                            [
                                'kode' => $kodeKabupaten,
                            ],
                            [
                                'nama' => $namaKabupaten,
                                'level' => 'kabupaten',
                                'parent_id' => $provinsiId,
                            ]
                        );

                        $kabupatenCache[$kodeKabupaten] =
                            $kabupaten->id;

                        $kabupatenCount++;
                    }

                    $kabupatenId =
                        $kabupatenCache[$kodeKabupaten];

                    /*
                    |--------------------------------------------------------------------------
                    | KECAMATAN
                    |--------------------------------------------------------------------------
                    */

                    if (!isset($kecamatanCache[$kodeKecamatan])) {

                        $kecamatan = Region::updateOrCreate(
                            [
                                'kode' => $kodeKecamatan,
                            ],
                            [
                                'nama' => $namaKecamatan,
                                'level' => 'kecamatan',
                                'parent_id' => $kabupatenId,
                            ]
                        );

                        $kecamatanCache[$kodeKecamatan] =
                            $kecamatan->id;

                        $kecamatanCount++;
                    }

                    $kecamatanId =
                        $kecamatanCache[$kodeKecamatan];

                    /*
                    |--------------------------------------------------------------------------
                    | DESA / KELURAHAN
                    |--------------------------------------------------------------------------
                    */

                    Region::updateOrCreate(
                        [
                            'kode' => $kodeDesa,
                        ],
                        [
                            'nama' => $namaDesa,
                            'level' => 'desa',
                            'parent_id' => $kecamatanId,
                        ]
                    );

                    $desaCount++;
                }
            });

            /*
            |--------------------------------------------------------------------------
            | PROGRESS
            |--------------------------------------------------------------------------
            */

            $processed = min(
                $endRow - 1,
                $highestRow - 1
            );

            $this->command->info(
                "Memproses {$processed} / " .
                ($highestRow - 1) .
                " baris..."
            );

            /*
            |--------------------------------------------------------------------------
            | BERSIHKAN MEMORY CHUNK
            |--------------------------------------------------------------------------
            */

            unset($rows);

            gc_collect_cycles();
        }

        /*
        |--------------------------------------------------------------------------
        | SELESAI
        |--------------------------------------------------------------------------
        */

        $spreadsheet->disconnectWorksheets();

        unset($spreadsheet);
        unset($sheet);

        gc_collect_cycles();

        $this->command->newLine();

        $this->command->info(
            "Provinsi : {$provinsiCount}"
        );

        $this->command->info(
            "Kabupaten/Kota : {$kabupatenCount}"
        );

        $this->command->info(
            "Kecamatan : {$kecamatanCount}"
        );

        $this->command->info(
            "Desa/Kelurahan : {$desaCount}"
        );

        $this->command->newLine();

        $this->command->info(
            'Import wilayah Indonesia selesai.'
        );
    }
}