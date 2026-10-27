<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterBagian extends Model
{
    protected $table = 'master_bagians';

    protected $fillable = [
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
