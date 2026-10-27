
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_work_schedules', function (Blueprint $table) {
            $table->id();

            $table->date('tanggal');
            $table->string('penempatan', 255);

            $table->foreignId('work_schedule_id')
                ->constrained('work_schedules')
                ->restrictOnDelete();

            $table->string('keterangan')->nullable();
            $table->timestamps();

            // Satu jadwal untuk satu unit pada satu tanggal.
            $table->unique(
                ['tanggal', 'penempatan'],
                'daily_schedule_date_placement_unique'
            );

            $table->index(['penempatan', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_work_schedules');
    }
};
