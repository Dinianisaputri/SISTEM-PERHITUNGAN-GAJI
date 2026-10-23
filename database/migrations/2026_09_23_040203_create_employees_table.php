<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // ID / PIN yang digunakan pada mesin fingerprint
            $table->string('fingerprint_pin')->unique();

            $table->string('nik')->nullable()->unique();
            $table->string('name');
            $table->string('bagian')->nullable();
            $table->string('jabatan')->nullable();

            // Contoh: Borongan, Bulanan, dll.
            $table->string('jenis_karyawan')->nullable();

            // Aktif / Nonaktif
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};