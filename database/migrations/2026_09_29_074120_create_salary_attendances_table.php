<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('salary_detail_id')
                ->constrained('salary_details')
                ->cascadeOnDelete();

            $table->foreignId('attendance_log_id')
                ->nullable()
                ->constrained('attendance_logs')
                ->nullOnDelete();

            $table->date('tanggal');

            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            // Durasi istirahat dalam jam
            $table->decimal('jam_istirahat', 5, 2)->default(1);

            // Hasil perhitungan
            $table->decimal('jam_kerja', 5, 2)->default(0);
            $table->decimal('jam_lembur', 5, 2)->default(0);
            $table->decimal('jam_lembur_biasa', 5, 2)->default(0);
            $table->decimal('jam_lembur_libur', 5, 2)->default(0);

            // Apakah tanggal tersebut masuk kalender hari libur
            $table->boolean('is_holiday')->default(false);

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->index([
                'salary_detail_id',
                'tanggal'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_attendances');
    }
};