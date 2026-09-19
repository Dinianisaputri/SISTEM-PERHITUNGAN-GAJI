<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklogs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('borongan_employee_id')
                ->constrained('borongan_employees')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            $table->time('scan_masuk')->nullable();
            $table->time('scan_pulang')->nullable();

            $table->decimal('jam_istirahat', 5, 2)->default(0);

            $table->decimal('total_jam_kerja', 8, 2)->default(0);

            $table->decimal('hourly_rate', 15, 2)->default(18000);

            $table->decimal('gaji', 15, 2)->default(0);

            $table->foreignId('salary_archive_id')
                ->nullable()
                ->constrained('salary_archives')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklogs');
    }
};