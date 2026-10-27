<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_detail_id',
        'attendance_log_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'jam_istirahat',
        'jam_kerja',
        'jam_lembur',
        'jam_lembur_biasa',
        'jam_lembur_libur',
        'is_holiday',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_istirahat' => 'decimal:2',
        'jam_kerja' => 'decimal:2',
        'jam_lembur' => 'decimal:2',
        'jam_lembur_biasa' => 'decimal:2',
        'jam_lembur_libur' => 'decimal:2',
        'is_holiday' => 'boolean',
    ];

    public function detail()
    {
        return $this->belongsTo(
            SalaryDetail::class,
            'salary_detail_id'
        );
    }

    public function attendanceLog()
    {
        return $this->belongsTo(
            AttendanceLog::class,
            'attendance_log_id'
        );
    }
}