<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoronganEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_id',
        'nik',
        'name',
        'bagian',
    ];
}