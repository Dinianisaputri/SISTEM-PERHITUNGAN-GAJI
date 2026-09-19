<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borongan_employees', function (Blueprint $table) {
            $table->id();

            $table->string('no_id')->unique();
            $table->string('nik')->nullable();
            $table->string('name');
            $table->string('bagian')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borongan_employees');
    }
};