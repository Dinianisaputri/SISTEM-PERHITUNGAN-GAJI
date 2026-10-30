
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('npwp', 30)->nullable();
            $table->boolean('bpjs_tk_terdaftar')->default(false);
            $table->string('no_bpjs_tk', 50)->nullable();
            $table->string('program_bpjs_tk', 100)->nullable();
            $table->boolean('bpjs_kesehatan_terdaftar')->default(false);
            $table->string('no_bpjs_kesehatan', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'npwp',
                'bpjs_tk_terdaftar',
                'no_bpjs_tk',
                'program_bpjs_tk',
                'bpjs_kesehatan_terdaftar',
                'no_bpjs_kesehatan',
            ]);
        });
    }
};
