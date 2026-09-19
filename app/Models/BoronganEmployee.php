<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoronganEmployee extends Model
{
    protected $fillable = [
        'no_id',
        'nik',
        'name',
        'bagian',
    ];

    public function checklogs(): HasMany
    {
        return $this->hasMany(Checklog::class);
    }
}