<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJabatan extends Model
{
    protected $table = 'master_jabatans';

    protected $fillable = [
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
