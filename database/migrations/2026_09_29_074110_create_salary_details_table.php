<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('salary_period_id')
                ->constrained('salary_periods')
                ->cascadeOnDelete();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->string('jenis_karyawan');

            // Pengaturan tarif
            $table->decimal('tarif_harian', 15, 2)->default(0);
            $table->decimal('tarif_jam', 15, 2)->default(0);
            $table->decimal('tarif_lembur_biasa', 15, 2)->default(18500);
            $table->decimal('tarif_lembur_libur', 15, 2)->default(37000);
            $table->decimal('tarif_uang_makan', 15, 2)->default(11000);

            // Pengaturan istirahat
            $table->decimal('jam_istirahat', 5, 2)->default(1);

            // Rekap hasil perhitungan
            $table->decimal('hari_kerja', 8, 2)->default(0);
            $table->decimal('jam_kerja', 8, 2)->default(0);
            $table->decimal('jam_lembur_biasa', 8, 2)->default(0);
            $table->decimal('jam_lembur_libur', 8, 2)->default(0);

            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('total_lembur', 15, 2)->default(0);
            $table->decimal('uang_makan_lembur', 15, 2)->default(0);
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->decimal('gaji_kotor', 15, 2)->default(0);
            $table->decimal('gaji_bersih', 15, 2)->default(0);

            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->unique([
                'salary_period_id',
                'employee_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_details');
    }
};