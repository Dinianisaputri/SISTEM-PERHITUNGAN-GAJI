
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('employees', 'surat_kontrak')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('surat_kontrak')->nullable();
            });
        }
    }

    public function down(): void
    {
        // The column is also created by the earlier HRD migration.
    }
};
