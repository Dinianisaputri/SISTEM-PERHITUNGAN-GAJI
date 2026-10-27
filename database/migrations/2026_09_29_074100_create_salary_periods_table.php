<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_periods', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');

            $table->string('status')->default('draft');

            $table->timestamps();

            $table->unique(['bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_periods');
    }
};