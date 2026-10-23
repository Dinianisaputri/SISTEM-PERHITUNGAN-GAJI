<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_salaries', function (Blueprint $table) {
            $table->id();

            // Karyawan
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            // Periode gaji
            $table->date('periode_mulai');
            $table->date('periode_selesai');

            // Perhitungan hari kerja
            $table->decimal('jumlah_hari_kerja', 5, 2)->default(0);
            $table->decimal('upah_harian', 15, 2)->default(0);
            $table->decimal('total_upah_harian', 15, 2)->default(0);

            // Lembur
            $table->decimal('lembur_biasa', 15, 2)->default(0);
            $table->decimal('tarif_lembur_biasa', 15, 2)->default(0);
            $table->decimal('total_lembur_biasa', 15, 2)->default(0);

            // Lembur hari libur
            $table->decimal('lembur_hari_libur', 15, 2)->default(0);
            $table->decimal('tarif_lembur_hari_libur', 15, 2)->default(0);
            $table->decimal('total_lembur_hari_libur', 15, 2)->default(0);

            // Lembur UM
            $table->decimal('lembur_um', 15, 2)->default(0);
            $table->decimal('tarif_lembur_um', 15, 2)->default(0);
            $table->decimal('total_lembur_um', 15, 2)->default(0);

            // Lain-lain
            $table->decimal('lain_lain', 15, 2)->default(0);

            // Potongan
            $table->decimal('potongan', 15, 2)->default(0);

            // Total
            $table->decimal('total_gaji', 15, 2)->default(0);

            $table->timestamps();

            // Satu karyawan hanya satu data untuk satu periode
            $table->unique([
                'employee_id',
                'periode_mulai',
                'periode_selesai'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_salaries');
    }
};