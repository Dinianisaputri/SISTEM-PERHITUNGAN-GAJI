<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyWorkSchedule extends Model
{
    protected $table = 'daily_work_schedules';

    protected $fillable = [
        'tanggal',
        'penempatan',
        'work_schedule_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function workSchedule(): BelongsTo
    {
        return $this->belongsTo(
            WorkSchedule::class,
            'work_schedule_id'
        );
    }
}