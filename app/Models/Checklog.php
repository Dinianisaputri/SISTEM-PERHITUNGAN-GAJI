<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Region;

class Checklog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
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
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'rt',
        'rw',
        'detail_alamat',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_istirahat' => 'decimal:2',
        'total_jam_kerja' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'gaji' => 'decimal:2',
    ];

    /**
     * Checklog milik satu karyawan
     * yang berasal dari Data Karyawan.
     */
    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id'
        );
    }

    /**
     * Checklog masuk ke arsip gaji
     */
    public function salaryArchive()
    {
        return $this->belongsTo(
            SalaryArchive::class,
            'salary_archive_id'
        );
    }
    public function provinsi()
{
    return $this->belongsTo(Region::class, 'provinsi_id');
}

public function kabupaten()
{
    return $this->belongsTo(Region::class, 'kabupaten_id');
}

public function kecamatan()
{
    return $this->belongsTo(Region::class, 'kecamatan_id');
}

public function desa()
{
    return $this->belongsTo(Region::class, 'desa_id');
}
}