<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_sn',
        'pin',
        'punched_at',
        'status',
        'verify',
        'work_code',
        'raw_data',
        'event_hash',
        'source_ip',
        'received_at',
        'source',
    ];

    protected $casts = [
        'punched_at' => 'datetime',
        'received_at' => 'datetime',
        'status' => 'integer',
        'verify' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'pin',
            'fingerprint_pin'
        );
    }
}