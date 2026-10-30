<?php

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

function seedEmployeeMasterOptions(): void
{
    foreach ([
        'master_bagians' => 'Fish Meal',
        'master_jabatans' => 'Operator',
    ] as $table => $name) {
        DB::table($table)->insert([
            'nama' => $name,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

it('renders the employee create page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/employees/create');

    $response->assertOk();
    $response->assertSee('Tambah Karyawan');
});

it('renders the employee edit page with the modern HR form', function () {
    $user = User::factory()->create();
    $employee = Employee::create([
        'name' => 'Test Karyawan',
        'bagian' => 'Fish Meal',
        'jabatan' => 'Operator',
        'jenis_karyawan' => 'Borongan',
        'is_active' => true,
        'jumlah_sp' => 0,
    ]);

    $response = $this->actingAs($user)->get('/employees/' . $employee->id . '/edit');

    $response->assertOk();
    $response->assertSee('Edit Karyawan');
    $response->assertSee('Data Identitas');
    $response->assertSee('Surat Kontrak');
});

it('creates an employee with the full profile fields including passport photo', function () {
    $user = User::factory()->create();
    seedEmployeeMasterOptions();

    $response = $this->actingAs($user)->post('/employees', [
        'name' => 'BAY ANDI LUKMAN',
        'bagian' => 'Fish Meal',
        'no_induk_karyawan' => '1170',
        'jabatan' => 'Operator',
        'jenis_karyawan' => 'Borongan',
        'is_active' => true,
        'jumlah_sp' => 0,
        'pas_foto' => UploadedFile::fake()->image('pasfoto.jpg', 300, 300),
        'surat_kontrak' => UploadedFile::fake()->create('surat-kontrak.pdf', 100, 'application/pdf'),
    ]);

    $response->assertRedirect(route('employees.index'));

    $this->assertDatabaseHas('employees', [
        'name' => 'BAY ANDI LUKMAN',
        'no_induk_karyawan' => '1170',
        'bagian' => 'Fish Meal',
        'jabatan' => 'Operator',
        'jenis_karyawan' => 'Borongan',
        'is_active' => 1,
        'jumlah_sp' => 0,
    ]);

    $employee = Employee::where('name', 'BAY ANDI LUKMAN')->first();

    expect($employee->no_induk_karyawan)->toBe('1170');
    expect($employee->pas_foto)->not->toBeNull();
    expect($employee->surat_kontrak)->not->toBeNull();
});

it('accepts harian as a valid employee type and shows it in the filter options', function () {
    $user = User::factory()->create();
    seedEmployeeMasterOptions();

    $createResponse = $this->actingAs($user)->get('/employees/create');
    $createResponse->assertSee('Harian');

    $response = $this->actingAs($user)->post('/employees', [
        'name' => 'Karyawan Harian',
        'bagian' => 'Fish Meal',
        'no_induk_karyawan' => '1171',
        'jabatan' => 'Operator',
        'jenis_karyawan' => 'Harian',
        'is_active' => true,
        'jumlah_sp' => 0,
    ]);

    $response->assertRedirect(route('employees.index'));
    $this->assertDatabaseHas('employees', [
        'name' => 'Karyawan Harian',
        'jenis_karyawan' => 'Harian',
    ]);
});
