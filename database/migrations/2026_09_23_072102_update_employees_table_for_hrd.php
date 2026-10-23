<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {

            $table->renameColumn('nik', 'no_induk_karyawan');

            $table->string('pendidikan')->nullable()->after('bagian');
            $table->string('status_pernikahan')->nullable()->after('pendidikan');
            $table->string('jenis_kelamin')->nullable()->after('status_pernikahan');

            $table->string('tempat_lahir')->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');

            $table->text('alamat_domisili')->nullable()->after('tanggal_lahir');

            $table->string('no_ktp')->nullable()->unique()->after('alamat_domisili');

            $table->date('mulai_kerja')->nullable()->after('no_ktp');
            $table->date('tanggal_kontrak')->nullable()->after('mulai_kerja');
            $table->date('selesai_kontrak')->nullable()->after('tanggal_kontrak');

            $table->unsignedInteger('kontrak_ke')
                ->nullable()
                ->after('selesai_kontrak');

            $table->string('foto_kk')
                ->nullable()
                ->after('kontrak_ke');

            $table->string('foto_ktp')
                ->nullable()
                ->after('foto_kk');

            $table->string('foto_biodata')
                ->nullable()
                ->after('foto_ktp');

            $table->string('surat_kontrak')
                ->nullable()
                ->after('foto_biodata');

            $table->unsignedInteger('jumlah_sp')
                ->default(0)
                ->after('surat_kontrak');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {

            $table->dropUnique(['no_ktp']);

            $table->dropColumn([
                'pendidikan',
                'status_pernikahan',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'alamat_domisili',
                'no_ktp',
                'mulai_kerja',
                'tanggal_kontrak',
                'selesai_kontrak',
                'kontrak_ke',
                'foto_kk',
                'foto_ktp',
                'foto_biodata',
                'surat_kontrak',
                'jumlah_sp',
            ]);

            $table->renameColumn(
                'no_induk_karyawan',
                'nik'
            );
        });
    }
};