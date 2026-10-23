<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'pin',
        'punched_at',
        'status',
        'verify',
        'device_serial',
    ];

    protected $casts = [
        'punched_at' => 'datetime',
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