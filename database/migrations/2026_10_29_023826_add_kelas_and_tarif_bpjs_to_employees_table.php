<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'kelas_bpjs_kesehatan')) {
                $table->string('kelas_bpjs_kesehatan', 20)->nullable();
            }

            if (!Schema::hasColumn('employees', 'tarif_bpjs')) {
                $table->decimal('tarif_bpjs', 15, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        // Tidak menghapus kolom agar data BPJS tetap aman.
    }
};
