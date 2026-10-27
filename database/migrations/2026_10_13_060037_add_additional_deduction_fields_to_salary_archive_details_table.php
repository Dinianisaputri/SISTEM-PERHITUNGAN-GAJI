```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_archive_details', function (Blueprint $table) {
            $table->decimal('potongan_tambahan', 15, 2)
                ->default(0)
                ->after('total_potongan');

            $table->text('alasan_potongan_tambahan')
                ->nullable()
                ->after('potongan_tambahan');
        });
    }

    public function down(): void
    {
        Schema::table('salary_archive_details', function (Blueprint $table) {
            $table->dropColumn([
                'potongan_tambahan',
                'alasan_potongan_tambahan',
            ]);
        });
    }
};