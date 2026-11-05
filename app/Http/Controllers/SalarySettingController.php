<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSalaryRate;
use App\Models\SalarySetting;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SalarySettingController extends Controller
{
    private array $defaults = [
        'borongan_per_jam' => [
            'label' => 'Tarif Borongan per Jam',
            'amount' => 18000,
            'unit' => 'jam',
        ],
        'harian_per_hari' => [
            'label' => 'Tarif Harian per Hari',
            'amount' => 127855,
            'unit' => 'hari',
        ],
        'lembur_biasa_per_jam' => [
            'label' => 'Tarif Lembur Biasa per Jam',
            'amount' => 18500,
            'unit' => 'jam',
        ],
        'lembur_libur_per_jam' => [
            'label' => 'Tarif Lembur Hari Libur per Jam',
            'amount' => 37000,
            'unit' => 'jam',
        ],
        'uang_makan_per_3_jam' => [
            'label' => 'Uang Makan per Hari Lembur',
            'amount' => 11000,
            'unit' => '3 jam',
        ],
        'bpjs_ketenagakerjaan' => [
            'label' => 'Potongan BPJS Ketenagakerjaan',
            'amount' => 0,
            'unit' => 'karyawan/periode'
        ]
    ];

    public function index(Request $request)
    {
        $settings = [];

        foreach ($this->defaults as $key => $default) {
            $setting = SalarySetting::firstOrCreate(
                ['key' => $key],
                $default
            );

            $settings[$key] = $setting;
        }

        $monthlyEmployeeQuery = Employee::query()
            ->where('is_active', true)
            ->whereRaw('LOWER(TRIM(jenis_karyawan)) = ?', ['bulanan']);

        $monthlyBagians = (clone $monthlyEmployeeQuery)
            ->whereNotNull('bagian')
            ->whereRaw("TRIM(bagian) <> ''")
            ->pluck('bagian')
            ->map(fn (string $bagian) => trim($bagian))
            ->unique()
            ->sort()
            ->values();

        $search = trim((string) $request->query('search', ''));
        $bagian = trim((string) $request->query('bagian', ''));

        $monthlyEmployees = $monthlyEmployeeQuery
            ->when($search !== '', fn ($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->when($bagian !== '', fn ($query) => $query->where('bagian', $bagian))
            ->with(['salaryRates' => fn ($query) => $query
                ->orderByDesc('effective_from')
                ->orderByDesc('id')])
            ->orderBy('name')
            ->get()
            ->map(function (Employee $employee) {
                $employee->setAttribute(
                    'active_salary_rate',
                    $employee->salaryRates->first(
                        fn (EmployeeSalaryRate $rate) => $rate->effective_from->lte(today())
                    )
                );

                return $employee;
            });

        return view('salary-settings.index', compact(
            'settings',
            'monthlyEmployees',
            'monthlyBagians',
            'search',
            'bagian'
        ));
    }

    public function storeMonthlyRate(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'amount' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'effective_from' => [
                'required',
                'date',
                Rule::unique('employee_salary_rates', 'effective_from')
                    ->where(fn ($query) => $query->where('employee_id', $employeeId)),
            ],
        ], [
            'effective_from.unique' => 'Tarif untuk karyawan ini sudah tercatat pada tanggal tersebut.',
        ]);

        $employee = Employee::query()->findOrFail($validated['employee_id']);

        if (strtolower(trim((string) $employee->jenis_karyawan)) !== 'bulanan') {
            return back()
                ->withInput()
                ->withErrors(['employee_id' => 'Tarif khusus ini hanya berlaku untuk karyawan bulanan.']);
        }

        EmployeeSalaryRate::create([
            'employee_id' => $employee->id,
            'amount' => $validated['amount'],
            'effective_from' => $validated['effective_from'],
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('salary-settings.index', array_filter(
                $request->only(['search', 'bagian']),
                fn ($value) => $value !== null && $value !== ''
            ))
            ->with('success', 'Tarif karyawan bulanan berhasil ditambahkan ke riwayat.');
    }

    public function destroyMonthlyRate(Request $request, Employee $employee, EmployeeSalaryRate $rate)
    {
        if ($rate->employee_id !== $employee->id) {
            throw new NotFoundHttpException();
        }

        $rate->delete();

        return redirect()
            ->route('salary-settings.index', array_filter(
                $request->only(['search', 'bagian']),
                fn ($value) => $value !== null && $value !== ''
            ))
            ->with('success', 'Tarif karyawan berhasil dihapus dari riwayat.');
    }

    public function update(Request $request)
    {
        $rules = [];

        foreach (array_keys($this->defaults) as $key) {
            $rules[$key] = ['required', 'numeric', 'min:0', 'max:999999999'];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated) {
            foreach ($this->defaults as $key => $default) {
                SalarySetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'label' => $default['label'],
                        'amount' => $validated[$key],
                        'unit' => $default['unit'],
                    ]
                );
            }
        });

        return redirect()
            ->route('salary-settings.index')
            ->with('success', 'Pengaturan tarif gaji berhasil disimpan.');
    }
}