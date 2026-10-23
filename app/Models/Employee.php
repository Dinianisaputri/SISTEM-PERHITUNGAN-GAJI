<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fingerprint_pin',
        'no_induk_karyawan',
        'name',
        'bagian',
        'jabatan',
        'jenis_karyawan',
        'pendidikan',
        'status_pernikahan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_domisili',
        'no_ktp',
        'pas_foto',
        'mulai_kerja',
        'tanggal_kontrak',
        'selesai_kontrak',
        'kontrak_ke',
        'foto_kk',
        'foto_ktp',
        'foto_biodata',
        'surat_kontrak',
        'jumlah_sp',
        'is_active',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'mulai_kerja' => 'date',
        'tanggal_kontrak' => 'date',
        'selesai_kontrak' => 'date',
        'jumlah_sp' => 'integer',
        'kontrak_ke' => 'integer',
        'is_active' => 'boolean',
    ];

    public function attendanceLogs()
    {
        return $this->hasMany(
            AttendanceLog::class,
            'pin',
            'fingerprint_pin'
        );
    }
}