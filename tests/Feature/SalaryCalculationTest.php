<?php

use App\Services\SalaryCalculationService;
use Carbon\Carbon;

it('treats a clock-in up to ten minutes after schedule as on time', function () {
    $service = new SalaryCalculationService;
    $schedule = (object) ['jam_masuk' => '08:00:00'];

    $withinTolerance = $service->applyLateArrivalTolerance(
        Carbon::parse('2026-01-05 08:10:00'),
        $schedule->jam_masuk
    );
    $outsideTolerance = $service->applyLateArrivalTolerance(
        Carbon::parse('2026-01-05 08:11:00'),
        $schedule->jam_masuk
    );

    expect($withinTolerance->format('H:i:s'))->toBe('08:00:00')
        ->and($outsideTolerance->format('H:i:s'))->toBe('08:11:00');
});

it('rounds hours up only when the remaining minutes reach 45', function () {
    $service = new SalaryCalculationService;

    expect($service->roundHoursToWhole(8 + (44 / 60)))->toBe(8)
        ->and($service->roundHoursToWhole(8 + (44 + (59 / 60)) / 60))->toBe(8)
        ->and($service->roundHoursToWhole(8 + (45 / 60)))->toBe(9);
});

it('starts borongan meal allowance at the rounded eleventh net work hour', function () {
    $service = new SalaryCalculationService;

    $justBeforeFortyFiveMinutes = $service->calculateBorongan(
        '08:00:00',
        '17:44:59',
        1,
        10000
    );
    $atFortyFiveMinutes = $service->calculateBorongan(
        '08:00:00',
        '17:45:00',
        1,
        10000
    );
    $underThreshold = $service->calculateBorongan(
        '07:00:00',
        '18:37:00',
        1,
        20000
    );

    $atThreshold = $service->calculateBorongan(
        '07:00:00',
        '18:45:00',
        1,
        20000
    );

    expect($justBeforeFortyFiveMinutes['jam_kerja'])->toBe(8.0)
        ->and($atFortyFiveMinutes['jam_kerja'])->toBe(9.0)
        ->and($underThreshold['jam_bruto'])->toBe(11.0)
        ->and($underThreshold['jam_kerja'])->toBe(10.0)
        ->and($underThreshold['uang_makan_lembur'])->toBe(0.0)
        ->and($underThreshold['gaji_pokok'])->toBe(200000.0)
        ->and($atThreshold['jam_bruto'])->toBe(12.0)
        ->and($atThreshold['jam_kerja'])->toBe(11.0)
        ->and($atThreshold['gaji_pokok'])->toBe(220000.0)
        ->and($atThreshold['uang_makan_lembur'])->toBe(11000.0);
});

it('rounds regular overtime from actual work minutes using the same 45-minute threshold', function () {
    $service = new SalaryCalculationService;

    $underThreshold = $service->calculateHarianBulanan(
        100000,
        8 + (44 / 60)
    );

    $atThreshold = $service->calculateHarianBulanan(
        100000,
        8 + (45 / 60)
    );

    expect($underThreshold['jam_lembur'])->toBe(0.0)
        ->and($atThreshold['jam_lembur'])->toBe(1.0);
});
