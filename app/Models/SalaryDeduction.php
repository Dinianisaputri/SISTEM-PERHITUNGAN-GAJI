<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_detail_id',
        'nama_potongan',
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function detail()
    {
        return $this->belongsTo(
            SalaryDetail::class,
            'salary_detail_id'
        );
    }
}