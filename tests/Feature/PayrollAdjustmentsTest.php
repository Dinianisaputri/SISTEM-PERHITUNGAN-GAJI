<?php

use App\Models\DailyWorkSchedule;
use App\Models\Employee;
use App\Models\EmployeeSalaryRate;
use App\Models\MasterBagian;
use App\Models\SalaryAddition;
use App\Models\SalaryAttendance;
use App\Models\SalaryArchive;
use App\Models\SalaryArchiveDetail;
use App\Models\SalaryDetail;
use App\Models\User;
use App\Models\WorkSchedule;
use App\Services\SalaryCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('assigns daily schedules by bagian and applies daily borongan break overrides', function () {
    $user = User::factory()->create();
    MasterBagian::create(['nama' => 'Dapur', 'is_active' => true]);
    $workSchedule = WorkSchedule::create([
        'kode' => 'D1',
        'nama' => 'Shift Dapur',
        'jam_masuk' => '08:00',
        'jam_pulang' => '18:00',
        'is_active' => true,
    ]);
    $employee = Employee::create([
        'fingerprint_pin' => '7001',
        'name' => 'Karyawan Borongan',
        'bagian' => 'Dapur',
        'penempatan' => 'Unit Lama',
        'jenis_karyawan' => 'Borongan',
        'is_active' => true,
    ]);
    $tanggal = '2026-01-05';

    $scheduleResponse = $this->actingAs($user)->post(
        route('daily-work-schedules.store'),
        [
            'tanggal' => $tanggal,
            'bagian' => 'Dapur',
            'work_schedule_id' => $workSchedule->id,
        ]
    );

    $scheduleResponse->assertRedirect(route('daily-work-schedules.index'));
    $scheduleResponse->assertSessionHasNoErrors();
    $dailySchedule = DailyWorkSchedule::firstOrFail();
    expect($dailySchedule->bagian)->toBe('Dapur');
    expect($dailySchedule->penempatan)->toBeNull();

    DB::table('attendance_logs')->insert([
        [
            'device_sn' => 'DEVICE-1',
            'pin' => '7001',
            'punched_at' => $tanggal . ' 08:00:00',
            'event_hash' => str_repeat('a', 64),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'device_sn' => 'DEVICE-1',
            'pin' => '7001',
            'punched_at' => $tanggal . ' 18:00:00',
            'event_hash' => str_repeat('b', 64),
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    $period = [
        'tanggal_mulai' => $tanggal,
        'tanggal_selesai' => $tanggal,
    ];

    $breakResponse = $this->actingAs($user)->put(
        route('salary.attendance.break.update', $employee->id),
        $period + [
            'tanggal' => $tanggal,
            'jam_istirahat' => '2.00',
        ]
    );

    $breakResponse->assertRedirect();
    expect((float) SalaryAttendance::firstOrFail()->jam_istirahat)->toBe(2.0);
    expect(SalaryDetail::firstOrFail()->attendances()->count())->toBe(1);

    $detailResponse = $this->actingAs($user)->get(
        route('salary.detail', ['employee' => $employee->id] + $period)
    );

    $detailResponse->assertOk();
    expect($detailResponse->viewData('salaryDetail'))->not->toBeNull();
    expect((float) $detailResponse->viewData('salaryDetail')->attendances()->first()->jam_istirahat)
        ->toBe(2.0);
    expect((float) $detailResponse->viewData('attendance')->first()['jam_istirahat'])
        ->toBe(2.0);
    $detailResponse->assertSee('08:00 - 18:00');
    $detailResponse->assertSee('value="2.00"', false);
    expect((float) $detailResponse->viewData('attendance')->first()['jam_kerja'])
        ->toBe(8.0);
    expect((float) $detailResponse->viewData('attendance')->first()['pendapatan_harian'])
        ->toBe(144000.0);
    $detailResponse->assertSee('Pendapatan Harian');
    $detailResponse->assertSee('Rp 144.000');
});

it('applies ten-minute clock-in tolerance to borongan calculations in payroll and detail', function () {
    $user = User::factory()->create();
    MasterBagian::create(['nama' => 'Produksi', 'is_active' => true]);
    $workSchedule = WorkSchedule::create([
        'kode' => 'P1',
        'nama' => 'Shift Produksi',
        'jam_masuk' => '08:00',
        'jam_pulang' => '18:00',
        'is_active' => true,
    ]);
    DailyWorkSchedule::create([
        'tanggal' => '2026-01-06',
        'bagian' => 'Produksi',
        'work_schedule_id' => $workSchedule->id,
    ]);

    $withinTolerance = Employee::create([
        'fingerprint_pin' => '7011',
        'name' => 'Borongan Toleransi',
        'bagian' => 'Produksi',
        'jenis_karyawan' => 'Borongan',
        'is_active' => true,
    ]);
    $outsideTolerance = Employee::create([
        'fingerprint_pin' => '7012',
        'name' => 'Borongan Lewat Toleransi',
        'bagian' => 'Produksi',
        'jenis_karyawan' => 'Borongan',
        'is_active' => true,
    ]);
    foreach ([
        ['pin' => '7011', 'masuk' => '08:10:00', 'pulang' => '17:54:00', 'hash' => 'c'],
        ['pin' => '7012', 'masuk' => '08:11:00', 'pulang' => '17:55:00', 'hash' => 'd'],
    ] as $attendance) {
        foreach (['masuk', 'pulang'] as $type) {
            $hash = hash('sha256', $attendance['hash'] . $type);
            DB::table('attendance_logs')->insert([
                'device_sn' => 'DEVICE-1',
                'pin' => $attendance['pin'],
                'punched_at' => '2026-01-06 ' . $attendance[$type],
                'event_hash' => $hash,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    $period = [
        'tanggal_mulai' => '2026-01-06',
        'tanggal_selesai' => '2026-01-06',
    ];

    $payrollResponse = $this->actingAs($user)->get(route('salary.index', $period));
    $payrollResponse->assertOk();
    $salaryRows = $payrollResponse->viewData('salaryEmployees')->keyBy(
        fn ($row) => $row['employee']->id
    );

    expect((float) $salaryRows[$withinTolerance->id]['attendance']->first()['jam_kerja'])
        ->toBe(9.0)
        ->and((float) $salaryRows[$outsideTolerance->id]['attendance']->first()['jam_kerja'])
        ->toBe(8.0);
    expect($salaryRows[$withinTolerance->id]['attendance']->first()['jam_masuk'])
        ->toBe('08:10:00');

    $detailResponse = $this->actingAs($user)->get(
        route('salary.detail', ['employee' => $withinTolerance->id] + $period)
    );
    $detailResponse->assertOk();
    expect((float) $detailResponse->viewData('attendance')->first()['jam_kerja'])
        ->toBe(9.0);
});

it('allows selecting and deleting daily work schedules in bulk', function () {
    $user = User::factory()->create();
    $workSchedule = WorkSchedule::create([
        'kode' => 'D2',
        'nama' => 'Shift Dapur',
        'jam_masuk' => '08:00',
        'jam_pulang' => '18:00',
        'is_active' => true,
    ]);
    $firstSchedule = DailyWorkSchedule::create([
        'tanggal' => '2026-01-05',
        'bagian' => 'Dapur',
        'work_schedule_id' => $workSchedule->id,
    ]);
    $secondSchedule = DailyWorkSchedule::create([
        'tanggal' => '2026-01-06',
        'bagian' => 'Dapur',
        'work_schedule_id' => $workSchedule->id,
    ]);

    $this->actingAs($user)
        ->get(route('daily-work-schedules.index'))
        ->assertOk()
        ->assertSee('data-bulk-select-all', false)
        ->assertSee('data-bulk-select', false)
        ->assertSee('Hapus yang dipilih');

    $this->delete(route('daily-work-schedules.bulk-destroy', ['tanggal' => '2026-01-05']), [
        'daily_schedule_ids' => [$firstSchedule->id],
    ])
        ->assertRedirect(route('daily-work-schedules.index', ['tanggal' => '2026-01-05']))
        ->assertSessionHas('success', '1 jadwal kerja harian berhasil dihapus.');

    $this->assertDatabaseMissing('daily_work_schedules', ['id' => $firstSchedule->id]);
    $this->assertDatabaseHas('daily_work_schedules', ['id' => $secondSchedule->id]);
});

it('stores payroll additions and includes them in the net salary', function () {
    $user = User::factory()->create();
    $employee = Employee::create([
        'fingerprint_pin' => '7002',
        'name' => 'Karyawan Tambahan',
        'jenis_karyawan' => 'Bulanan',
        'is_active' => true,
    ]);
    $period = [
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-01-31',
    ];

    $response = $this->actingAs($user)->post(
        route('salary.addition.store', $employee->id),
        $period + [
            'nama_tambahan' => 'Bonus',
            'nominal' => '50000',
            'keterangan_tambahan' => 'Insentif',
        ]
    );

    $response->assertRedirect();
    $addition = SalaryAddition::firstOrFail();
    $detail = SalaryDetail::firstOrFail();

    expect($addition->nama_tambahan)->toBe('Bonus');
    expect((float) $detail->total_tambahan)->toBe(50000.0);
    expect((float) $detail->gaji_bersih)->toBe(50000.0);

    $archiveResponse = $this->actingAs($user)->post(
        route('salary.archive'),
        $period
    );

    $archiveResponse->assertRedirect(route('salary_archives.index'));
    $archiveDetail = SalaryArchiveDetail::firstOrFail();
    expect((float) $archiveDetail->total_tambahan)->toBe(50000.0);
    expect($archiveDetail->rincian_nominal_tambahan[0]['nama_tambahan'])
        ->toBe('Bonus');

    $slipView = $this->view('salary_archives.slip_pdf', [
        'salaryArchive' => SalaryArchive::findOrFail($archiveDetail->salary_archive_id),
        'details' => collect([$archiveDetail]),
    ]);
    $slipView->assertSee('Bonus')
        ->assertSee('Total nominal tambahan')
        ->assertSee('+Rp 50.000');

    $this->get(route('salary_archives.slipPdf', $archiveDetail->salary_archive_id))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');

    $updateResponse = $this->actingAs($user)->put(
        route('salary.addition.update', [$employee->id, $addition->id]),
        $period + [
            'nama_tambahan' => 'Bonus Revisi',
            'nominal' => '75000',
            'keterangan_tambahan' => 'Insentif revisi',
        ]
    );

    $updateResponse->assertRedirect();
    expect((float) $detail->fresh()->total_tambahan)->toBe(75000.0);
    expect((float) $detail->fresh()->gaji_bersih)->toBe(75000.0);

    $deleteResponse = $this->actingAs($user)->delete(
        route('salary.addition.destroy', [$employee->id, $addition->id]),
        $period
    );

    $deleteResponse->assertRedirect();
    expect((float) $detail->fresh()->total_tambahan)->toBe(0.0);
    expect((float) $detail->fresh()->gaji_bersih)->toBe(0.0);
});

it('keeps individual monthly employee rates by effective date and snapshots them in payroll details', function () {
    $user = User::factory()->create();
    $employee = Employee::create([
        'fingerprint_pin' => '7003',
        'name' => 'Karyawan Bulanan Bertarif',
        'jenis_karyawan' => 'Bulanan',
        'is_active' => true,
    ]);

    $this->actingAs($user)->post(route('salary-settings.monthly-rates.store'), [
        'employee_id' => $employee->id,
        'amount' => 125000,
        'effective_from' => '2026-01-01',
    ])->assertRedirect(route('salary-settings.index'))
        ->assertSessionHasNoErrors();

    $firstPeriod = [
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-01-31',
    ];

    $firstDetailPage = $this->get(
        route('salary.detail', ['employee' => $employee->id] + $firstPeriod)
    );
    $firstDetailPage->assertOk();
    expect((float) $firstDetailPage->viewData('tarifHarian'))->toBe(125000.0);

    $this->post(
        route('salary.detail.update', $employee->id),
        $firstPeriod + [
            'tarif_harian' => 999999,
            'jam_istirahat' => 1,
        ]
    )->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->post(route('salary.archive'), $firstPeriod)
        ->assertRedirect(route('salary_archives.index'));

    expect((float) SalaryArchiveDetail::query()
        ->where('employee_id', $employee->id)
        ->firstOrFail()
        ->tarif_harian)->toBe(125000.0);

    $this->actingAs($user)->post(route('salary-settings.monthly-rates.store'), [
        'employee_id' => $employee->id,
        'amount' => 150000,
        'effective_from' => '2026-06-01',
    ])->assertRedirect(route('salary-settings.index'))
        ->assertSessionHasNoErrors();

    expect(EmployeeSalaryRate::query()->where('employee_id', $employee->id)->count())->toBe(2);
    expect((float) SalaryDetail::query()->where('employee_id', $employee->id)->firstOrFail()->tarif_harian)
        ->toBe(125000.0);

    $secondPeriod = [
        'tanggal_mulai' => '2026-06-01',
        'tanggal_selesai' => '2026-06-30',
    ];

    $secondDetailPage = $this->get(
        route('salary.detail', ['employee' => $employee->id] + $secondPeriod)
    );
    $secondDetailPage->assertOk();
    expect((float) $secondDetailPage->viewData('tarifHarian'))->toBe(150000.0);

    $this->get(route('salary-settings.index'))
        ->assertOk()
        ->assertSee('Tarif Harian Karyawan Bulanan')
        ->assertSee('Riwayat tarif (2)');

    $secondRate = EmployeeSalaryRate::query()
        ->where('employee_id', $employee->id)
        ->whereDate('effective_from', '2026-06-01')
        ->firstOrFail();

    $this->delete(route('salary-settings.monthly-rates.destroy', [$employee->id, $secondRate->id]))
        ->assertRedirect(route('salary-settings.index'));

    $this->assertDatabaseMissing('employee_salary_rates', ['id' => $secondRate->id]);
});

it('pays one daily meal allowance when regular overtime reaches three hours', function () {
    $calculator = app(SalaryCalculationService::class);
    $schedule = (object) ['jam_pulang' => '16:00:00'];

    $noOvertime = $calculator->calculateHarianBulanan(
        127855,
        8,
        false,
        false,
        $schedule,
        '2026-01-05',
        '16:00:00'
    );
    $oneHourOvertime = $calculator->calculateHarianBulanan(
        127855,
        9,
        false,
        false,
        $schedule,
        '2026-01-05',
        '17:00:00'
    );
    $twoHoursOvertime = $calculator->calculateHarianBulanan(
        127855,
        10,
        false,
        false,
        $schedule,
        '2026-01-05',
        '18:00:00'
    );
    $threeHoursOvertime = $calculator->calculateHarianBulanan(
        127855,
        10.75,
        false,
        false,
        $schedule,
        '2026-01-05',
        '18:45:00'
    );
    $fortyFourMinutesOvertime = $calculator->calculateHarianBulanan(
        127855,
        8.73,
        false,
        false,
        $schedule,
        '2026-01-05',
        '16:44:00'
    );
    $fortyFiveMinutesOvertime = $calculator->calculateHarianBulanan(
        127855,
        8.75,
        false,
        false,
        $schedule,
        '2026-01-05',
        '16:45:00'
    );
    $fiveHours = $calculator->calculateHarianBulanan(
        127855,
        13,
        false,
        false,
        $schedule,
        '2026-01-05',
        '21:00:00'
    );
    $sevenHours = $calculator->calculateHarianBulanan(
        127855,
        15,
        false,
        false,
        $schedule,
        '2026-01-05',
        '23:00:00'
    );

    expect((float) $noOvertime['uang_makan_lembur'])->toBe(0.0);
    expect((float) $oneHourOvertime['uang_makan_lembur'])->toBe(0.0);
    expect((float) $twoHoursOvertime['uang_makan_lembur'])->toBe(0.0);
    expect((float) $fortyFourMinutesOvertime['jam_lembur'])->toBe(0.0);
    expect((float) $fortyFourMinutesOvertime['uang_makan_lembur'])->toBe(0.0);
    expect((float) $fortyFiveMinutesOvertime['jam_lembur'])->toBe(1.0);
    expect((float) $fortyFiveMinutesOvertime['uang_makan_lembur'])->toBe(0.0);
    expect((float) $threeHoursOvertime['jam_lembur'])->toBe(3.0);
    expect((float) $threeHoursOvertime['uang_makan_lembur'])->toBe(11000.0);
    expect((float) $fiveHours['uang_makan_lembur'])->toBe(11000.0);
    expect((float) $sevenHours['uang_makan_lembur'])->toBe(11000.0);
});

it('pays one daily meal allowance when holiday overtime reaches three hours', function () {
    $calculator = app(SalaryCalculationService::class);

    $noOvertime = $calculator->calculateHarianBulanan(
        127855,
        0,
        true
    );
    $twoHoursOvertime = $calculator->calculateHarianBulanan(
        127855,
        2,
        true
    );
    $threeHoursOvertime = $calculator->calculateHarianBulanan(
        127855,
        3,
        true
    );

    expect((float) $noOvertime['uang_makan_lembur'])->toBe(0.0);
    expect((float) $twoHoursOvertime['uang_makan_lembur'])->toBe(0.0);
    expect((float) $threeHoursOvertime['uang_makan_lembur'])->toBe(11000.0);
});

it('pays borongan meal allowance once from the eleventh net work hour', function () {
    $calculator = app(SalaryCalculationService::class);

    $beforeEleventhHour = $calculator->calculateBorongan('07:00:00', '17:59:00', 1, 10000);
    $tenNetHours = $calculator->calculateBorongan('07:00:00', '18:00:00', 1, 10000);
    $elevenNetHours = $calculator->calculateBorongan('07:00:00', '19:00:00', 1, 10000);

    expect((float) $beforeEleventhHour['uang_makan_lembur'])->toBe(0.0);
    expect((float) $tenNetHours['jam_kerja'])->toBe(10.0);
    expect((float) $tenNetHours['uang_makan_lembur'])->toBe(0.0);
    expect((float) $elevenNetHours['jam_kerja'])->toBe(11.0);
    expect((float) $elevenNetHours['uang_makan_lembur'])->toBe(11000.0);
});

it('counts at most one meal allowance per date in payroll totals', function () {
    $totals = app(SalaryCalculationService::class)->calculateTotalPeriode([
        [
            'tanggal' => '2026-01-05',
            'jam_kerja' => 8,
            'uang_makan_lembur' => 11000,
        ],
        [
            'tanggal' => '2026-01-05',
            'jam_kerja' => 8,
            'uang_makan_lembur' => 11000,
        ],
    ]);

    expect((float) $totals['uang_makan_lembur'])->toBe(11000.0);
});

it('filters monthly employee salary rates by name and bagian', function () {
    $user = User::factory()->create();
    $firstEmployee = Employee::create([
        'fingerprint_pin' => '7101',
        'name' => 'Andi Bagian Dapur',
        'bagian' => 'Dapur',
        'jenis_karyawan' => 'Bulanan',
        'is_active' => true,
    ]);
    Employee::create([
        'fingerprint_pin' => '7102',
        'name' => 'Budi Bagian Gudang',
        'bagian' => 'Gudang',
        'jenis_karyawan' => 'Bulanan',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get(route('salary-settings.index', [
        'search' => 'Andi',
        'bagian' => 'Dapur',
    ]));

    $response->assertOk()
        ->assertSee('Tarif Harian Karyawan Bulanan')
        ->assertSee('Cari nama karyawan')
        ->assertSee('Bagian')
        ->assertSee('Andi Bagian Dapur')
        ->assertDontSee('Budi Bagian Gudang');
    expect($response->viewData('monthlyEmployees')->pluck('id')->all())
        ->toBe([$firstEmployee->id]);
});
