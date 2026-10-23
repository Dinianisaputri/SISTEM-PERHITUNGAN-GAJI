<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChecklogController;
use App\Http\Controllers\SalaryArchiveController;
use App\Http\Controllers\AdmsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\RegulationController;
    use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryDashboardController;
use App\Http\Controllers\DailySalaryController;
/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Semua Fitur Sistem
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Data Karyawan
    |--------------------------------------------------------------------------
    */

    Route::get('/data-karyawan', [EmployeeController::class, 'dashboard'])
        ->name('employees.dashboard');

    Route::resource('employees', EmployeeController::class);


    /*
    |--------------------------------------------------------------------------
    | Data Fingerprint
    |--------------------------------------------------------------------------
    */

    Route::get('/data-fingerprint', [AttendanceLogController::class, 'index'])
        ->name('attendance.index');

    Route::get('/data-fingerprint/belum-terdaftar', [AttendanceController::class, 'unregistered'])
    ->name('attendance.unregistered');
    /*
    |--------------------------------------------------------------------------
    | Regulasi
    |--------------------------------------------------------------------------
    */

    // Dashboard Regulasi
    Route::get('/regulations/dashboard',
        [RegulationController::class, 'dashboard']
    )->name('regulations.dashboard');

    // Download Dokumen Regulasi
    Route::get('/regulations/{regulation}/download',
        [RegulationController::class, 'download']
    )->name('regulations.download');

    // CRUD Regulasi
    Route::resource('regulations', RegulationController::class);


    /*
    |--------------------------------------------------------------------------
    | Gaji Karyawan - Hitung Gaji
    |--------------------------------------------------------------------------
    */

    Route::get('/hitung-gaji', [ChecklogController::class, 'index'])
        ->name('checklogs.index');

    Route::post('/hitung-gaji/import', [ChecklogController::class, 'import'])
        ->name('checklogs.import');

    Route::post('/hitung-gaji/hitung-semua', [ChecklogController::class, 'hitungSemua'])
        ->name('checklogs.hitungSemua');

    Route::post('/hitung-gaji/simpan-arsip', [ChecklogController::class, 'simpanArsip'])
        ->name('checklogs.simpanArsip');


    /*
    |--------------------------------------------------------------------------
    | Gaji Karyawan - Arsip Gaji
    |--------------------------------------------------------------------------
    */

    Route::get('/arsip-gaji', [SalaryArchiveController::class, 'index'])
        ->name('salary_archives.index');

    Route::get('/arsip-gaji/{salaryArchive}', [SalaryArchiveController::class, 'show'])
        ->name('salary_archives.show');

    Route::delete('/arsip-gaji/{salaryArchive}', [SalaryArchiveController::class, 'destroy'])
        ->name('salary_archives.destroy');

    Route::get(
        '/arsip-gaji/{salaryArchive}/checklog/{checklog}/edit',
        [SalaryArchiveController::class, 'editChecklog']
    )->name('salary_archives.editChecklog');

    Route::put(
        '/arsip-gaji/{salaryArchive}/checklog/{checklog}',
        [SalaryArchiveController::class, 'updateChecklog']
    )->name('salary_archives.updateChecklog');

    Route::delete(
        '/arsip-gaji/{salaryArchive}/checklog/{checklog}',
        [SalaryArchiveController::class, 'destroyChecklog']
    )->name('salary_archives.destroyChecklog');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| ADMS Fingerprint Machine
|--------------------------------------------------------------------------
|
| Route ini tidak memakai middleware auth karena
| mesin fingerprint harus bisa mengirim data langsung.
|
*/

Route::get('/iclock/cdata', [AdmsController::class, 'cdataGet']);

Route::post('/iclock/cdata', [AdmsController::class, 'cdataPost']);

Route::get('/iclock/getrequest', [AdmsController::class, 'getRequest']);

Route::post('/iclock/devicecmd', [AdmsController::class, 'deviceCmd']);

Route::get('/iclock/registry', [AdmsController::class, 'registry']);


/*
|--------------------------------------------------------------------------
| Test Attendance
|--------------------------------------------------------------------------
*/

Route::get(
    '/attendance-test',
    [App\Http\Controllers\AttendanceController::class, 'index']
);


require __DIR__ . '/auth.php';
Route::get('/gaji-karyawan', [SalaryDashboardController::class, 'index'])
    ->name('salary.dashboard');

Route::get('/gaji-harian', [DailySalaryController::class, 'index'])
    ->name('daily_salaries.index');

Route::post('/gaji-harian', [DailySalaryController::class, 'store'])
    ->name('daily_salaries.store');