
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warning_notices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete();

            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->text('alasan_pelanggaran');

            $table->date('tanggal_mulai_berlaku');
            $table->date('tanggal_akhir_berlaku');

            $table->string('nama_kepala_unit');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warning_notices');
    }
};
