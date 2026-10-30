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
use App\Http\Controllers\AccountCredentialController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\EmployeeWorkScheduleImportController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Controllers\DailyWorkScheduleController;
use App\Http\Controllers\SalarySettingController;
use App\Http\Controllers\MasterBagianController;
use App\Http\Controllers\MasterJabatanController;
use App\Http\Controllers\MasterPenempatanController;
use App\Http\Controllers\MasterKategoriRegulationController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\WarningNoticeController;
use App\Http\Controllers\ContractController;      
use App\Http\Controllers\MasterBpjsController;
/*
|--------------------------------------------------------------------------
| Landing Page Publik
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Endpoint Fingerprint ADMS
|--------------------------------------------------------------------------
| Endpoint ini harus bisa diakses mesin fingerprint tanpa login.
*/

Route::get('/iclock/cdata', [AdmsController::class, 'cdataGet']);
Route::post('/iclock/cdata', [AdmsController::class, 'cdataPost']);
Route::get('/iclock/getrequest', [AdmsController::class, 'getRequest']);
Route::post('/iclock/devicecmd', [AdmsController::class, 'deviceCmd']);
Route::get('/iclock/registry', [AdmsController::class, 'registry']);

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Fitur Internal Sistem HRD dan Penggajian
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/beranda', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Data Karyawan
   
    // Data Karyawan
    Route::get('/data-karyawan', [EmployeeController::class, 'dashboard'])
        ->name('employees.dashboard');

    Route::get('/employees/regions', [EmployeeController::class, 'getRegions'])
        ->name('employees.regions');

    // Export PDF harus diletakkan sebelum resource employees
    Route::get('/employees/export-pdf', [EmployeeController::class, 'exportPdf'])
        ->name('employees.export-pdf');

    Route::resource('employees', EmployeeController::class);

    // Master Jam Kerja dan Jadwal Harian
    Route::resource('work-schedules', WorkScheduleController::class)
        ->except(['show']);

    Route::resource('daily-work-schedules', DailyWorkScheduleController::class)
        ->except(['show', 'destroy']);

    Route::post(
        '/gaji-karyawan/import-jadwal',
        [EmployeeWorkScheduleImportController::class, 'store']
    )->name('salary.import.schedule');

    // Pengaturan Tarif Gaji
    Route::get('/pengaturan-tarif', [SalarySettingController::class, 'index'])
        ->name('salary-settings.index');

    Route::put('/pengaturan-tarif', [SalarySettingController::class, 'update'])
        ->name('salary-settings.update');

    Route::get('/hitung-gaji', function () {
        return redirect()->route('salary-settings.index');
    })->name('checklogs.index');

    // Data Fingerprint dan Absensi
    Route::get('/data-fingerprint', [AttendanceLogController::class, 'index'])
        ->name('attendance.index');

    Route::get('/data-fingerprint/belum-terdaftar', [AttendanceController::class, 'unregistered'])
        ->name('attendance.unregistered');

    Route::post(
        '/data-fingerprint/import-excel',
        [AttendanceLogController::class, 'importExcel']
    )->name('attendance.import.excel');

    Route::post(
        '/data-fingerprint/import-dat',
        [AttendanceLogController::class, 'importDat']
    )->name('attendance.import.dat');

    Route::get('/attendance-test', [AttendanceController::class, 'index'])
        ->name('attendance.test');

    // Hari Libur
    Route::resource('holidays', HolidayController::class)
        ->except(['show']);

    // Regulasi
    Route::get('/regulations/dashboard', [RegulationController::class, 'dashboard'])
        ->name('regulations.dashboard');
    // Surat Peringatan
Route::resource(
    'warning-letters',
    \App\Http\Controllers\WarningLetterController::class
);
    Route::get('/regulations/{regulation}/download', [RegulationController::class, 'download'])
        ->name('regulations.download');

    Route::resource('regulations', RegulationController::class);

    // Penggajian
    Route::get('/gaji', [SalaryController::class, 'index'])
        ->name('salary.index');

    Route::get('/gaji/{employee}/detail', [SalaryController::class, 'detail'])
        ->name('salary.detail');

    Route::post('/gaji/{employee}/detail', [SalaryController::class, 'updateDetail'])
        ->name('salary.detail.update');

    Route::post('/gaji/{employee}/detail/potongan', [SalaryController::class, 'storeDeduction'])
        ->name('salary.deduction.store');

    Route::put(
        '/gaji/{employee}/detail/potongan/{deduction}',
        [SalaryController::class, 'updateDeduction']
    )->name('salary.deduction.update');

    Route::delete(
        '/gaji/{employee}/detail/potongan/{deduction}',
        [SalaryController::class, 'destroyDeduction']
    )->name('salary.deduction.destroy');

    Route::match(['GET', 'POST'], '/gaji/arsipkan', [SalaryController::class, 'archive'])
        ->name('salary.archive');

    // Arsip Gaji
    Route::get('/arsip-gaji', [SalaryArchiveController::class, 'index'])
        ->name('salary_archives.index');

    Route::get('/arsip-gaji/{salaryArchive}', [SalaryArchiveController::class, 'show'])
        ->name('salary_archives.show');

        Route::get(
    '/arsip-gaji/{salaryArchive}/export-finance',
    [SalaryArchiveController::class, 'exportFinance']
)->name('salary_archives.exportFinance');

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

    Route::get(
        '/arsip-gaji/{salaryArchive}/detail/{detail}/edit',
        [SalaryArchiveController::class, 'editSalaryDetail']
    )->name('salary_archives.details.edit');

    Route::put(
        '/arsip-gaji/{salaryArchive}/detail/{detail}',
        [SalaryArchiveController::class, 'updateSalaryDetail']
    )->name('salary_archives.details.update');

    // Data Akun
    Route::resource('data-akun', AccountCredentialController::class)
        ->parameters([
            'data-akun' => 'account',
        ])
        ->names([
            'index' => 'accounts.index',
            'create' => 'accounts.create',
            'store' => 'accounts.store',
            'edit' => 'accounts.edit',
            'update' => 'accounts.update',
            'destroy' => 'accounts.destroy',
        ]);

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
        Route::get('/employees/export-pdf', [
    EmployeeController::class,
    'exportPdf'
])->name('employees.export-pdf');
});

Route::get(
    '/arsip-gaji/{salaryArchive}/slip-pdf',
    [SalaryArchiveController::class, 'slipPdf']
)->name('salary_archives.slipPdf');

Route::post(
    '/arsip-gaji/export-finance-terpilih',
    [SalaryArchiveController::class, 'exportFinanceSelected']
)->name('salary_archives.export-finance-selected');

/*
|--------------------------------------------------------------------------
| Master Data HRD
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('master-data')
    ->name('master-data.')
    ->group(function () {
        // Bagian
        Route::get('/bagian', [MasterBagianController::class, 'index'])
            ->name('bagian.index');
        Route::post('/bagian', [MasterBagianController::class, 'store'])
            ->name('bagian.store');
        Route::put('/bagian/{masterBagian}', [MasterBagianController::class, 'update'])
            ->name('bagian.update');
        Route::patch('/bagian/{masterBagian}/toggle', [MasterBagianController::class, 'toggle'])
            ->name('bagian.toggle');

        // Jabatan
        Route::get('/jabatan', [MasterJabatanController::class, 'index'])
            ->name('jabatan.index');
        Route::post('/jabatan', [MasterJabatanController::class, 'store'])
            ->name('jabatan.store');
        Route::put('/jabatan/{masterJabatan}', [MasterJabatanController::class, 'update'])
            ->name('jabatan.update');
        Route::patch('/jabatan/{masterJabatan}/toggle', [MasterJabatanController::class, 'toggle'])
            ->name('jabatan.toggle');

        // Penempatan
        Route::get('/penempatan', [MasterPenempatanController::class, 'index'])
            ->name('penempatan.index');
        Route::post('/penempatan', [MasterPenempatanController::class, 'store'])
            ->name('penempatan.store');
        Route::put('/penempatan/{masterPenempatan}', [MasterPenempatanController::class, 'update'])
            ->name('penempatan.update');
        Route::patch('/penempatan/{masterPenempatan}/toggle', [MasterPenempatanController::class, 'toggle'])
            ->name('penempatan.toggle');

        // Kategori Regulasi
        Route::get('/kategori-regulasi', [MasterKategoriRegulationController::class, 'index'])
            ->name('kategori-regulasi.index');
        Route::post('/kategori-regulasi', [MasterKategoriRegulationController::class, 'store'])
            ->name('kategori-regulasi.store');
        Route::put('/kategori-regulasi/{masterKategoriRegulation}', [MasterKategoriRegulationController::class, 'update'])
            ->name('kategori-regulasi.update');
        Route::patch('/kategori-regulasi/{masterKategoriRegulation}/toggle', [MasterKategoriRegulationController::class, 'toggle'])
            ->name('kategori-regulasi.toggle');
    });
Route::get(
    'warning-letters/{warningLetter}/print',
    [\App\Http\Controllers\WarningLetterController::class, 'print']
)->name('warning-letters.print');


Route::get(
    'warning-notices/{warningNotice}/print',
    [WarningNoticeController::class, 'print']
)->name('warning-notices.print');

Route::get(
    'contracts/{contract}/print',
    [ContractController::class, 'print']
)->name('contracts.print');

Route::resource('warning-notices', WarningNoticeController::class);
Route::resource('contracts', ContractController::class);


Route::middleware(['auth'])->group(function () {
    Route::view('/panduan', 'panduan.index')
        ->name('panduan.index');

// Master BPJS
Route::get('/master-bpjs', [MasterBpjsController::class, 'index'])
    ->name('master-bpjs.index');

Route::put('/master-bpjs/{employee}/tarif', [
    MasterBpjsController::class,
    'updateTarif'
])->name('master-bpjs.update-tarif');

Route::post(
    '/gaji/{employee}/potong-bpjs',
    [SalaryController :: class, 'toggleBpjs'
    ])->name ('salary.bpjs.toggle');
});