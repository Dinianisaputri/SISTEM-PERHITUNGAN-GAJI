<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPenempatan extends Model
{
    protected $table = 'master_penempatans';

    protected $fillable = [
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
