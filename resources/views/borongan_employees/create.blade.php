@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')

<div style="
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
">

    <div>
        <h1 class="page-title">
            Tambah Karyawan
        </h1>

        <p class="page-description" style="margin-bottom: 0;">
            Tambahkan data karyawan borongan yang akan digunakan sebagai patokan fingerprint.
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
        action="{{ route('employees.store') }}"
        method="POST"
    >

        @csrf

        <div class="form-group">

            <label for="no_id">
                No. ID
            </label>

            <input
                type="text"
                id="no_id"
                name="no_id"
                value="{{ old('no_id') }}"
                placeholder="Contoh: 1006"
                required
            >

            <small style="
                display: block;
                margin-top: 6px;
                color: #6b7280;
            ">
                No. ID harus sama dengan ID karyawan pada data fingerprint.
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
                value="{{ old('nik') }}"
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
                value="{{ old('name') }}"
                placeholder="Contoh: ASYHURI"
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
                value="{{ old('bagian') }}"
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
                Simpan Karyawan
            </button>

        </div>

    </form>

</div>

@endsection