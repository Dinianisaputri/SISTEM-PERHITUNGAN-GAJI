<?php

use App\Models\Employee;
use App\Models\MasterBagian;
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

it('saves a newly added master bagian when updating an employee', function () {
    $user = User::factory()->create();
    seedEmployeeMasterOptions();
    MasterBagian::create(['nama' => 'Bagian Baru']);
    DB::table('master_penempatans')->insert([
        'nama' => 'Unit Uji',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $employee = Employee::create([
        'name' => 'Karyawan Uji Update',
        'bagian' => 'Fish Meal',
        'jabatan' => 'Operator',
        'penempatan' => 'Unit Uji',
    ]);

    $this->actingAs($user)
        ->get(route('employees.edit', $employee))
        ->assertOk()
        ->assertSee('Bagian Baru');

    $this->put(route('employees.update', $employee), [
        'name' => $employee->name,
        'bagian' => 'Bagian Baru',
        'jabatan' => '',
        'penempatan' => 'Unit Uji',
        'is_active' => '1',
        'jumlah_sp' => '0',
    ])->assertRedirect(route('employees.index'))
        ->assertSessionHas('success', 'Data karyawan berhasil diperbarui.')
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'bagian' => 'Bagian Baru',
        'jabatan' => null,
    ]);
});

it('preserves existing employee select values when they are missing from current options', function () {
    $user = User::factory()->create();
    seedEmployeeMasterOptions();
    $employee = Employee::create([
        'name' => 'Karyawan Data Lama',
        'bagian' => 'Bagian Lama',
        'jabatan' => 'Jabatan Lama',
        'penempatan' => 'Unit Lama',
        'jenis_karyawan' => 'Kontrak Lama',
        'pendidikan' => 'Paket C',
        'status_pernikahan' => 'Menikah',
        'jenis_kelamin' => 'Pria',
        'program_bpjs_tk' => 'Program Lama',
        'no_bpjs_tk' => 'TK-123',
        'kelas_bpjs_kesehatan' => 'Kelas 3',
        'no_bpjs_kesehatan' => 'KES-123',
    ]);

    $response = $this->actingAs($user)
        ->get(route('employees.edit', $employee))
        ->assertOk();

    foreach ([
        'Bagian Lama',
        'Jabatan Lama',
        'Unit Lama',
        'Kontrak Lama',
        'Paket C',
        'Menikah',
        'Pria',
        'Program Lama',
        'Kelas 3',
    ] as $existingValue) {
        expect($response->getContent())
            ->toMatch('/<option\s+value="' . preg_quote($existingValue, '/') . '"\s+selected\s*>/');
    }

    $this->put(route('employees.update', $employee), [
        'name' => 'Karyawan Data Lama Diperbarui',
        'bagian' => 'Bagian Lama',
        'jabatan' => 'Jabatan Lama',
        'penempatan' => 'Unit Lama',
        'jenis_karyawan' => 'Kontrak Lama',
        'pendidikan' => 'Paket C',
        'status_pernikahan' => 'Menikah',
        'jenis_kelamin' => 'Pria',
        'program_bpjs_tk' => 'Program Lama',
        'no_bpjs_tk' => 'TK-123',
        'kelas_bpjs_kesehatan' => 'Kelas 3',
        'no_bpjs_kesehatan' => 'KES-123',
        'is_active' => '1',
        'jumlah_sp' => '0',
    ])->assertRedirect(route('employees.index'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'name' => 'Karyawan Data Lama Diperbarui',
        'bagian' => 'Bagian Lama',
        'jabatan' => 'Jabatan Lama',
        'penempatan' => 'Unit Lama',
        'jenis_karyawan' => 'Kontrak Lama',
        'pendidikan' => 'Paket C',
        'status_pernikahan' => 'Menikah',
        'jenis_kelamin' => 'Pria',
        'program_bpjs_tk' => 'Program Lama',
        'no_bpjs_tk' => 'TK-123',
        'kelas_bpjs_kesehatan' => 'Kelas 3',
        'no_bpjs_kesehatan' => 'KES-123',
    ]);
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

it('automatically registers BPJS when its type and number are provided', function () {
    $user = User::factory()->create();
    seedEmployeeMasterOptions();

    $response = $this->actingAs($user)->post('/employees', [
        'name' => 'Karyawan BPJS',
        'bagian' => 'Fish Meal',
        'no_induk_karyawan' => '1172',
        'jabatan' => 'Operator',
        'jenis_karyawan' => 'Borongan',
        'is_active' => true,
        'jumlah_sp' => 0,
        'kelas_bpjs_kesehatan' => 'Kelas 1',
        'no_bpjs_kesehatan' => '1234567890',
        'bpjs_kesehatan_terdaftar' => '0',
    ]);

    $response->assertRedirect(route('employees.index'));
    $this->assertDatabaseHas('employees', [
        'name' => 'Karyawan BPJS',
        'kelas_bpjs_kesehatan' => 'Kelas 1',
        'no_bpjs_kesehatan' => '1234567890',
        'bpjs_kesehatan_terdaftar' => 1,
    ]);
});

it('includes legacy BPJS data in master even when its stored registration flag is false', function () {
    $user = User::factory()->create();
    $employee = Employee::create([
        'name' => 'Karyawan BPJS Lama',
        'bagian' => 'Fish Meal',
        'jabatan' => 'Operator',
        'jenis_karyawan' => 'Borongan',
        'bpjs_kesehatan_terdaftar' => false,
        'kelas_bpjs_kesehatan' => 'Kelas 2',
        'no_bpjs_kesehatan' => '9876543210',
    ]);

    expect($employee->bpjs_kesehatan_terdaftar)->toBeTrue();

    $response = $this->actingAs($user)->get(route('master-bpjs.index'));
    $response->assertOk();
    $response->assertSee('Karyawan BPJS Lama');
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
        'jenis_karyawan' => 'Harian',
        'is_active' => true,
        'jumlah_sp' => 0,
    ]);

    $response->assertRedirect(route('employees.index'));
    $this->assertDatabaseHas('employees', [
        'name' => 'Karyawan Harian',
        'jenis_karyawan' => 'Harian',
        'jabatan' => null,
    ]);
});
