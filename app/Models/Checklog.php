<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checklog extends Model
{
    protected $fillable = [
        'borongan_employee_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'scan_masuk',
        'scan_pulang',
        'jam_istirahat',
        'total_jam_kerja',
        'hourly_rate',
        'gaji',
        'salary_archive_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_istirahat' => 'decimal:2',
        'total_jam_kerja' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'gaji' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(BoronganEmployee::class, 'borongan_employee_id');
    }

    public function archive(): BelongsTo
    {
        return $this->belongsTo(SalaryArchive::class, 'salary_archive_id');
    }
}