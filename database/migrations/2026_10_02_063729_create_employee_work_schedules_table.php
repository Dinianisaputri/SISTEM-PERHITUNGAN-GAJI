<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_work_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->foreignId('work_schedule_id')
                ->constrained('work_schedules')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->string('keterangan')->nullable();

            $table->timestamps();

            $table->unique(
                ['employee_id', 'tanggal'],
                'employee_schedule_per_tanggal_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_work_schedules');
    }
};