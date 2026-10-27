<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_archive_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('salary_archive_id')
                ->constrained('salary_archives')
                ->cascadeOnDelete();

            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->string('fingerprint_pin')->nullable();
            $table->string('employee_name');
            $table->string('bagian')->nullable();
            $table->string('jenis_karyawan')->nullable();

            $table->decimal('hari_hadir', 10, 2)->default(0);
            $table->decimal('jam_kerja', 10, 2)->default(0);
            $table->decimal('jam_lembur', 10, 2)->default(0);
            $table->decimal('jam_lembur_biasa', 10, 2)->default(0);
            $table->decimal('jam_lembur_libur', 10, 2)->default(0);

            $table->decimal('tarif_harian', 15, 2)->default(0);
            $table->decimal('tarif_jam', 15, 2)->default(0);
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('total_lembur', 15, 2)->default(0);
            $table->decimal('uang_makan_lembur', 15, 2)->default(0);
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->decimal('gaji_kotor', 15, 2)->default(0);
            $table->decimal('gaji_bersih', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_archive_details');
    }
};