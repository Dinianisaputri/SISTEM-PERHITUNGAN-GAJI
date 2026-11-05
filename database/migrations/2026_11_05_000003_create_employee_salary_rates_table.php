<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_salary_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('effective_from');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['employee_id', 'effective_from']);
        });

        $legacyRates = DB::table('employees as employees')
            ->join('salary_details as details', 'details.employee_id', '=', 'employees.id')
            ->join('salary_periods as periods', 'periods.id', '=', 'details.salary_period_id')
            ->whereRaw('LOWER(TRIM(employees.jenis_karyawan)) = ?', ['bulanan'])
            ->where('details.tarif_harian', '>', 0)
            ->orderBy('periods.tanggal_mulai')
            ->orderBy('details.id')
            ->select([
                'employees.id as employee_id',
                'details.tarif_harian as amount',
                'periods.tanggal_mulai as effective_from',
            ])
            ->get();

        $now = now();

        foreach ($legacyRates->groupBy('employee_id') as $employeeRates) {
            $ratesByEffectiveDate = $employeeRates->groupBy('effective_from');

            foreach ($ratesByEffectiveDate as $effectiveDate => $ratesForDate) {
                $rate = $ratesForDate->last();

                DB::table('employee_salary_rates')->insertOrIgnore([
                    'employee_id' => $rate->employee_id,
                    'amount' => $rate->amount,
                    'effective_from' => $effectiveDate,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_salary_rates');
    }
};
