<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalarySetting extends Model
{
    protected $fillable = [
        'key',
        'label',
        'amount',
        'unit',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public static function value(string $key, float $default = 0): float
    {
        $setting = static::where('key', $key)->first();

        return $setting ? (float) $setting->amount : $default;
    }
}