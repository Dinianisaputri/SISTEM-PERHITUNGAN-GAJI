<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_work_schedules', function (Blueprint $table) {
            $table->string('bagian')->nullable()->after('penempatan');
            $table->dropUnique('daily_schedule_date_placement_unique');
            $table->string('penempatan')->nullable()->change();
            $table->unique(
                ['tanggal', 'bagian'],
                'daily_schedule_date_bagian_unique'
            );
            $table->index(
                ['bagian', 'tanggal'],
                'daily_schedule_bagian_date_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('daily_work_schedules', function (Blueprint $table) {
            $table->dropUnique('daily_schedule_date_bagian_unique');
            $table->dropIndex('daily_schedule_bagian_date_index');
            $table->dropColumn('bagian');
            $table->string('penempatan')->nullable(false)->change();
            $table->unique(
                ['tanggal', 'penempatan'],
                'daily_schedule_date_placement_unique'
            );
        });
    }
};
