<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete();

            $table->string('nomor_kontrak')->unique();

            $table->string('jenis_kontrak');
            $table->string('bagian')->nullable();

            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir')->nullable();

            $table->string('status')->default('aktif');
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};