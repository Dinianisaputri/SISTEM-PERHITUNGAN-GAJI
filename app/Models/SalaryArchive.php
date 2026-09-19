<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryArchive extends Model
{
    protected $fillable = [
        'archive_name',
        'start_date',
        'end_date',
        'total_salary',
        'total_checklogs',
        'total_employees',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_salary' => 'decimal:2',
    ];

    public function checklogs(): HasMany
    {
        return $this->hasMany(Checklog::class, 'salary_archive_id');
    }
}