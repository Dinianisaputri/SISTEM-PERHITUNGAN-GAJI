<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();

            // Kode jadwal dari HRD, misalnya PRD SR 1, PRD SR 2, P16, S
            $table->string('kode')->unique();

            // Nama/keterangan jadwal
            $table->string('nama');

            // Jam kerja yang ditetapkan HRD
            $table->time('jam_masuk');
            $table->time('jam_pulang');

            // Keterangan tambahan jika diperlukan
            $table->text('keterangan')->nullable();

            // Jadwal masih digunakan atau tidak
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};