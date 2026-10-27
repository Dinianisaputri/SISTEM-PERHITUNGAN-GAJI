<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryArchiveDetail extends Model
{
    protected $fillable = [
        'salary_archive_id',
        'employee_id',
        'fingerprint_pin',
        'employee_name',
        'bagian',
        'jenis_karyawan',
        'hari_hadir',
        'jam_kerja',
        'jam_lembur',
        'jam_lembur_biasa',
        'jam_lembur_libur',
        'tarif_harian',
        'tarif_jam',
        'gaji_pokok',
        'total_lembur',
        'uang_makan_lembur',
        'total_potongan',
        'rincian_potongan',
        'rincian_potongan_tambahan',
        'gaji_kotor',
        'gaji_bersih',
        'potongan_tambahan',
        'alasan_potongan_tambahan',
        'daily_breakdown',
    ];

    protected function casts(): array
    {
        return [
            'rincian_potongan' => 'array',
            'rincian_potongan_tambahan' => 'array',
            'daily_breakdown' => 'array',
        ];
    }

    public function archive(): BelongsTo
    {
        return $this->belongsTo(
            SalaryArchive::class,
            'salary_archive_id'
        );
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}