<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_archives', function (Blueprint $table) {
            $table->id();

            $table->string('archive_name');

            $table->date('start_date');
            $table->date('end_date');

            $table->decimal('total_salary', 15, 2)->default(0);
            $table->integer('total_checklogs')->default(0);
            $table->integer('total_employees')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_archives');
    }
};