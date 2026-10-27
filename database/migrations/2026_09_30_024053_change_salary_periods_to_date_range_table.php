<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_periods', function (Blueprint $table) {
            $table->date('tanggal_mulai')->after('id');
            $table->date('tanggal_selesai')->after('tanggal_mulai');
        });

        // Hapus unique bulan + tahun setelah kolom baru tersedia
        Schema::table('salary_periods', function (Blueprint $table) {
            $table->dropUnique(['bulan', 'tahun']);
            $table->dropColumn(['bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::table('salary_periods', function (Blueprint $table) {
            $table->unsignedTinyInteger('bulan')->nullable();
            $table->unsignedSmallInteger('tahun')->nullable();
        });

        Schema::table('salary_periods', function (Blueprint $table) {
            $table->unique(['bulan', 'tahun']);

            $table->dropColumn([
                'tanggal_mulai',
                'tanggal_selesai',
            ]);
        });
    }
};