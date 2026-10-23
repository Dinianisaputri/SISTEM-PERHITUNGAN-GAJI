<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',

        'periode_mulai',
        'periode_selesai',

        'jumlah_hari_kerja',
        'upah_harian',
        'total_upah_harian',

        'lembur_biasa',
        'tarif_lembur_biasa',
        'total_lembur_biasa',

        'lembur_hari_libur',
        'tarif_lembur_hari_libur',
        'total_lembur_hari_libur',

        'lembur_um',
        'tarif_lembur_um',
        'total_lembur_um',

        'lain_lain',
        'potongan',

        'total_gaji',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',

        'jumlah_hari_kerja' => 'decimal:2',
        'upah_harian' => 'decimal:2',
        'total_upah_harian' => 'decimal:2',

        'lembur_biasa' => 'decimal:2',
        'tarif_lembur_biasa' => 'decimal:2',
        'total_lembur_biasa' => 'decimal:2',

        'lembur_hari_libur' => 'decimal:2',
        'tarif_lembur_hari_libur' => 'decimal:2',
        'total_lembur_hari_libur' => 'decimal:2',

        'lembur_um' => 'decimal:2',
        'tarif_lembur_um' => 'decimal:2',
        'total_lembur_um' => 'decimal:2',

        'lain_lain' => 'decimal:2',
        'potongan' => 'decimal:2',
        'total_gaji' => 'decimal:2',
    ];

    /**
     * Karyawan yang menerima gaji harian.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Mengambil seluruh fingerprint karyawan.
     *
     * Relasi AttendanceLog sebenarnya berada melalui:
     * Employee.fingerprint_pin -> AttendanceLog.pin
     *
     * Jadi pengambilan absensi akan dilakukan melalui
     * $dailySalary->employee->attendanceLogs
     */
}