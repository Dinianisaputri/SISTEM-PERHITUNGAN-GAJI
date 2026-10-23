<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('device_sn')->index();
            $table->string('pin')->index();
            $table->dateTime('punched_at')->index();
            $table->integer('status')->nullable();
            $table->integer('verify')->nullable();
            $table->string('work_code')->nullable();
            $table->text('raw_data')->nullable();
            $table->string('event_hash', 64)->unique();
            $table->ipAddress('source_ip')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};