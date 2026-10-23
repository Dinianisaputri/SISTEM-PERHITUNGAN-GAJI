<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regulations', function (Blueprint $table) {
            $table->id();

            // Kategori dan jenis perijinan
            $table->string('kategori');
            $table->string('jenis_perijinan');

            // Informasi regulasi
            $table->text('regulasi')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->string('nomor_ijin')->nullable();

            // Masa berlaku
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->string('masa_berlaku')->nullable();

            // Bagian terkait
            $table->string('bagian_terkait')->nullable();

            // Status pemenuhan
            $table->enum('status', ['Terpenuhi', 'Tidak Terpenuhi'])
                ->default('Terpenuhi');

            // Keterangan
            $table->text('keterangan')->nullable();

            // Dokumen
            $table->string('dokumen')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulations');
    }
};