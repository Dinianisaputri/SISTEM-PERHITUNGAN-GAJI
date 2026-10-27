<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('provinsi_id')
                ->nullable()
                ->after('alamat_domisili')
                ->constrained('regions')
                ->nullOnDelete();

            $table->foreignId('kabupaten_id')
                ->nullable()
                ->after('provinsi_id')
                ->constrained('regions')
                ->nullOnDelete();

            $table->foreignId('kecamatan_id')
                ->nullable()
                ->after('kabupaten_id')
                ->constrained('regions')
                ->nullOnDelete();

            $table->foreignId('desa_id')
                ->nullable()
                ->after('kecamatan_id')
                ->constrained('regions')
                ->nullOnDelete();

            $table->string('rt', 10)
                ->nullable()
                ->after('desa_id');

            $table->string('rw', 10)
                ->nullable()
                ->after('rt');

            $table->text('detail_alamat')
                ->nullable()
                ->after('rw');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['provinsi_id']);
            $table->dropForeign(['kabupaten_id']);
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['desa_id']);

            $table->dropColumn([
                'provinsi_id',
                'kabupaten_id',
                'kecamatan_id',
                'desa_id',
                'rt',
                'rw',
                'detail_alamat',
            ]);
        });
    }
};