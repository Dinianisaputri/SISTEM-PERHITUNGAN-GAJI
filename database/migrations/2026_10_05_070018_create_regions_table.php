<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();

            $table->string('kode')->unique();

            $table->string('nama');

            $table->enum('level', [
                'provinsi',
                'kabupaten',
                'kecamatan',
                'desa',
            ]);

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('regions')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['level', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};