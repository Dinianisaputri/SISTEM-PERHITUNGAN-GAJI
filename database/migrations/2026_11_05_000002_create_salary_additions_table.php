<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_details', function (Blueprint $table) {
            $table->decimal('total_tambahan', 15, 2)->default(0);
        });

        Schema::create('salary_additions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_detail_id')
                ->constrained('salary_details')
                ->cascadeOnDelete();
            $table->string('nama_tambahan');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::table('salary_archive_details', function (Blueprint $table) {
            $table->decimal('total_tambahan', 15, 2)->default(0);
            $table->json('rincian_nominal_tambahan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('salary_archive_details', function (Blueprint $table) {
            $table->dropColumn(['total_tambahan', 'rincian_nominal_tambahan']);
        });

        Schema::dropIfExists('salary_additions');

        Schema::table('salary_details', function (Blueprint $table) {
            $table->dropColumn('total_tambahan');
        });
    }
};
