<?php

use App\Models\DailyWorkSchedule;
use App\Models\Employee;
use App\Models\MasterBagian;
use App\Models\MasterJabatan;
use App\Models\MasterKategoriRegulation;
use App\Models\MasterPenempatan;
use App\Models\Regulation;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('deletes unused HR master values instead of toggling their status', function () {
    $user = User::factory()->create();
    $bagian = MasterBagian::create(['nama' => 'Bagian Uji', 'is_active' => false]);
    $jabatan = MasterJabatan::create(['nama' => 'Jabatan Uji', 'is_active' => false]);
    $penempatan = MasterPenempatan::create(['nama' => 'Penempatan Uji', 'is_active' => false]);
    $kategori = MasterKategoriRegulation::create(['nama' => 'Kategori Uji', 'is_active' => false]);

    $this->actingAs($user)
        ->delete(route('master-data.bagian.destroy', $bagian))
        ->assertRedirect();
    $this->delete(route('master-data.jabatan.destroy', $jabatan))
        ->assertRedirect();
    $this->delete(route('master-data.penempatan.destroy', $penempatan))
        ->assertRedirect();
    $this->delete(route('master-data.kategori-regulasi.destroy', $kategori))
        ->assertRedirect();

    $this->assertDatabaseMissing('master_bagians', ['id' => $bagian->id]);
    $this->assertDatabaseMissing('master_jabatans', ['id' => $jabatan->id]);
    $this->assertDatabaseMissing('master_penempatans', ['id' => $penempatan->id]);
    $this->assertDatabaseMissing('master_kategori_regulations', ['id' => $kategori->id]);

    MasterBagian::create(['nama' => 'Bagian Baru']);

    $this->get(route('master-data.bagian.index'))
        ->assertOk()
        ->assertSee('Hapus')
        ->assertDontSee('Nonaktifkan');
});

it('prevents deleting master data that is still referenced', function () {
    $user = User::factory()->create();
    $bagian = MasterBagian::create(['nama' => 'Bagian Terpakai']);
    $jabatan = MasterJabatan::create(['nama' => 'Jabatan Terpakai']);
    $penempatan = MasterPenempatan::create(['nama' => 'Penempatan Terpakai']);
    $kategori = MasterKategoriRegulation::create(['nama' => 'Kategori Terpakai']);

    Employee::create([
        'fingerprint_pin' => '8001',
        'name' => 'Karyawan Referensi',
        'bagian' => $bagian->nama,
        'jabatan' => $jabatan->nama,
        'penempatan' => $penempatan->nama,
        'is_active' => true,
    ]);

    Regulation::create([
        'kategori' => $kategori->nama,
        'jenis_perijinan' => 'Izin Uji',
    ]);

    $this->actingAs($user)
        ->delete(route('master-data.bagian.destroy', $bagian))
        ->assertSessionHasErrors('delete');
    $this->delete(route('master-data.jabatan.destroy', $jabatan))
        ->assertSessionHasErrors('delete');
    $this->delete(route('master-data.penempatan.destroy', $penempatan))
        ->assertSessionHasErrors('delete');
    $this->delete(route('master-data.kategori-regulasi.destroy', $kategori))
        ->assertSessionHasErrors('delete');

    $this->assertDatabaseHas('master_bagians', ['id' => $bagian->id]);
    $this->assertDatabaseHas('master_jabatans', ['id' => $jabatan->id]);
    $this->assertDatabaseHas('master_penempatans', ['id' => $penempatan->id]);
    $this->assertDatabaseHas('master_kategori_regulations', ['id' => $kategori->id]);
});

it('keeps previously inactive master values available for selection', function () {
    $user = User::factory()->create();
    MasterBagian::create(['nama' => 'Bagian Lama', 'is_active' => false]);
    MasterJabatan::create(['nama' => 'Jabatan Lama', 'is_active' => false]);
    MasterPenempatan::create(['nama' => 'Penempatan Lama', 'is_active' => false]);
    WorkSchedule::create([
        'kode' => 'L1',
        'nama' => 'Jadwal Lama',
        'jam_masuk' => '08:00',
        'jam_pulang' => '17:00',
        'is_active' => false,
    ]);

    $this->actingAs($user)
        ->get(route('employees.create'))
        ->assertOk()
        ->assertSee('Bagian Lama')
        ->assertSee('Jabatan Lama')
        ->assertSee('Penempatan Lama');

    $this->get(route('daily-work-schedules.create'))
        ->assertOk()
        ->assertSee('Bagian Lama')
        ->assertSee('Jadwal Lama');
});

it('deletes unused work schedules and protects schedules assigned to dates', function () {
    $user = User::factory()->create();
    $unusedSchedule = WorkSchedule::create([
        'kode' => 'U1',
        'nama' => 'Jadwal Tidak Terpakai',
        'jam_masuk' => '08:00',
        'jam_pulang' => '17:00',
        'is_active' => false,
    ]);

    $this->actingAs($user)
        ->delete(route('work-schedules.destroy', $unusedSchedule))
        ->assertRedirect(route('work-schedules.index'));
    $this->assertDatabaseMissing('work_schedules', ['id' => $unusedSchedule->id]);

    $usedSchedule = WorkSchedule::create([
        'kode' => 'U2',
        'nama' => 'Jadwal Terpakai',
        'jam_masuk' => '08:00',
        'jam_pulang' => '17:00',
    ]);

    DailyWorkSchedule::create([
        'tanggal' => '2026-02-01',
        'bagian' => 'Bagian Uji',
        'work_schedule_id' => $usedSchedule->id,
    ]);

    $this->delete(route('work-schedules.destroy', $usedSchedule))
        ->assertSessionHasErrors('delete');
    $this->assertDatabaseHas('work_schedules', ['id' => $usedSchedule->id]);
});
