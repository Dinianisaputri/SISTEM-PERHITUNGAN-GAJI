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
        'archive_type',
        'filters',
        'daily_breakdown',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'filters' => 'array',
            'daily_breakdown' => 'array',
        ];
    }

    public function checklogs(): HasMany
    {
        return $this->hasMany(Checklog::class, 'salary_archive_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(
            SalaryArchiveDetail::class,
            'salary_archive_id'
        );
    }
}