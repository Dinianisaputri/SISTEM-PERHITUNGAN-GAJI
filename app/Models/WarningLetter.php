<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarningLetter extends Model
{
    protected $fillable = [
        'employee_id',
        'nomor_surat',
        'tanggal_surat',
        'tingkat_sp',
        'alasan_pelanggaran',
        'tanggal_mulai_berlaku',
        'tanggal_akhir_berlaku',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_mulai_berlaku' => 'date',
            'tanggal_akhir_berlaku' => 'date',
            'tingkat_sp' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}