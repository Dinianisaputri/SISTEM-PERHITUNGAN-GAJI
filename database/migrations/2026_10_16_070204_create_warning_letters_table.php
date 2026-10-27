<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

Schema::create('warning_letters', function (Blueprint $table) {
    $table->id();

    // Relasi ke data karyawan
    $table->foreignId('employee_id')
        ->constrained('employees')
        ->restrictOnDelete();

    // Identitas surat
    $table->string('nomor_surat')->unique();
    $table->date('tanggal_surat');

    // Detail Surat Peringatan
    $table->unsignedTinyInteger('tingkat_sp');
    $table->text('alasan_pelanggaran');

    // Masa berlaku surat
    $table->date('tanggal_mulai_berlaku');
    $table->date('tanggal_akhir_berlaku')->nullable();

    // Status dan keterangan tambahan
    $table->string('status')->default('aktif');
    $table->text('keterangan')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warning_letters');
    }
};
