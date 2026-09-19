<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChecklogController;
use App\Http\Controllers\SalaryArchiveController;
use App\Http\Controllers\BoronganEmployeeController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Semua fitur sistem harus login
Route::middleware('auth')->group(function () {

    // Hitung Gaji
    Route::get('/hitung-gaji', [ChecklogController::class, 'index'])
        ->name('checklogs.index');

    Route::post('/hitung-gaji/import', [ChecklogController::class, 'import'])
        ->name('checklogs.import');

    Route::post('/hitung-gaji/hitung-semua', [ChecklogController::class, 'hitungSemua'])
        ->name('checklogs.hitungSemua');

    Route::post('/hitung-gaji/simpan-arsip', [ChecklogController::class, 'simpanArsip'])
        ->name('checklogs.simpanArsip');


    // Data Karyawan
    Route::get('/data-karyawan', [BoronganEmployeeController::class, 'index'])
        ->name('employees.index');

    Route::get('/data-karyawan/tambah', [BoronganEmployeeController::class, 'create'])
        ->name('employees.create');

    Route::post('/data-karyawan', [BoronganEmployeeController::class, 'store'])
        ->name('employees.store');

    Route::get('/data-karyawan/{employee}/edit', [BoronganEmployeeController::class, 'edit'])
        ->name('employees.edit');

    Route::put('/data-karyawan/{employee}', [BoronganEmployeeController::class, 'update'])
        ->name('employees.update');

    Route::delete('/data-karyawan/{employee}', [BoronganEmployeeController::class, 'destroy'])
        ->name('employees.destroy');


    // Arsip Gaji
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


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';