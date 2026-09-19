@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')

<div style="
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
">

    <div>
        <h1 class="page-title">
            Edit Karyawan
        </h1>

        <p class="page-description" style="margin-bottom: 0;">
            Perbarui data karyawan borongan.
        </p>
    </div>

    <a
        href="{{ route('employees.index') }}"
        class="btn btn-secondary"
    >
        ← Kembali
    </a>

</div>


<div class="card">

    <form
        action="{{ route('employees.update', $employee) }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        <div class="form-group">

            <label for="no_id">
                No. ID
            </label>

            <input
                type="text"
                id="no_id"
                name="no_id"
                value="{{ old('no_id', $employee->no_id) }}"
                required
            >

            <small style="
                display: block;
                margin-top: 6px;
                color: #6b7280;
            ">
                No. ID harus sesuai dengan ID pada data fingerprint.
            </small>

        </div>


        <div class="form-group">

            <label for="nik">
                NIK
            </label>

            <input
                type="text"
                id="nik"
                name="nik"
                value="{{ old('nik', $employee->nik) }}"
                placeholder="Masukkan NIK jika tersedia"
            >

        </div>


        <div class="form-group">

            <label for="name">
                Nama Karyawan
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $employee->name) }}"
                required
            >

        </div>


        <div class="form-group">

            <label for="bagian">
                Bagian
            </label>

            <input
                type="text"
                id="bagian"
                name="bagian"
                value="{{ old('bagian', $employee->bagian) }}"
                placeholder="Contoh: Produksi"
            >

        </div>


        <div style="
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        ">

            <a
                href="{{ route('employees.index') }}"
                class="btn btn-secondary"
            >
                Batal
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection