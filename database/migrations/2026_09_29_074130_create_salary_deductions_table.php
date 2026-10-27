<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_deductions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('salary_detail_id')
                ->constrained('salary_details')
                ->cascadeOnDelete();

            $table->string('nama_potongan');

            $table->decimal('nominal', 15, 2);

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_deductions');
    }
};