<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKategoriRegulation extends Model
{
    protected $table = 'master_kategori_regulations';

    protected $fillable = [
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
