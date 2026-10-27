<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarningNotice extends Model
{
    protected $fillable = [
        'employee_id',
        'nomor_surat',
        'tanggal_surat',
        'alasan_pelanggaran',
        'tanggal_mulai_berlaku',
        'tanggal_akhir_berlaku',
        'nama_kepala_unit',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_mulai_berlaku' => 'date',
            'tanggal_akhir_berlaku' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
