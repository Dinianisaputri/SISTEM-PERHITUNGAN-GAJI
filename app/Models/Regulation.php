<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori',
        'jenis_perijinan',
        'regulasi',
        'nomor_surat',
        'nomor_ijin',
        'tanggal_mulai',
        'tanggal_berakhir',
        'masa_berlaku',
        'bagian_terkait',
        'status',
        'keterangan',
        'dokumen',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
    ];
}