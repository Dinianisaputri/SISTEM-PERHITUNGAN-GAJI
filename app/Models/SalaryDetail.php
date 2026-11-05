<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_period_id',
        'employee_id',
        'jenis_karyawan',

        'tarif_harian',
        'tarif_jam',
        'tarif_lembur_biasa',
        'tarif_lembur_libur',
        'tarif_uang_makan',

        'jam_istirahat',

        'hari_kerja',
        'jam_kerja',
        'jam_lembur_biasa',
        'jam_lembur_libur',

        'gaji_pokok',
        'total_lembur',
        'uang_makan_lembur',
        'total_potongan',
        'total_tambahan',
        'gaji_kotor',
        'gaji_bersih',

        'catatan',
    ];

    protected $casts = [
        'tarif_harian' => 'decimal:2',
        'tarif_jam' => 'decimal:2',
        'tarif_lembur_biasa' => 'decimal:2',
        'tarif_lembur_libur' => 'decimal:2',
        'tarif_uang_makan' => 'decimal:2',

        'jam_istirahat' => 'decimal:2',

        'hari_kerja' => 'decimal:2',
        'jam_kerja' => 'decimal:2',
        'jam_lembur_biasa' => 'decimal:2',
        'jam_lembur_libur' => 'decimal:2',

        'gaji_pokok' => 'decimal:2',
        'total_lembur' => 'decimal:2',
        'uang_makan_lembur' => 'decimal:2',
        'total_potongan' => 'decimal:2',
        'total_tambahan' => 'decimal:2',
        'gaji_kotor' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
    ];

    public function period()
    {
        return $this->belongsTo(SalaryPeriod::class, 'salary_period_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function attendances()
    {
        return $this->hasMany(SalaryAttendance::class);
    }

    public function deductions()
    {
        return $this->hasMany(SalaryDeduction::class);
    }

    public function additions()
    {
        return $this->hasMany(SalaryAddition::class);
    }
}