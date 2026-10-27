<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_archive_details', function (Blueprint $table) {
            $table->json('rincian_potongan_tambahan')
                ->nullable()
                ->after('rincian_potongan');
        });
    }

    public function down(): void
    {
        Schema::table('salary_archive_details', function (Blueprint $table) {
            $table->dropColumn('rincian_potongan_tambahan');
        });
    }
};