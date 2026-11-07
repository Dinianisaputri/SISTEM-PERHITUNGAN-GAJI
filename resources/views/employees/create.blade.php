@extends('layouts.app')
@section('title', 'Tambah Karyawan')
@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Tambah Karyawan
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Tambahkan data identitas dan pekerjaan karyawan.
        </p>
    </div>
    @if ($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4">
            <ul class="text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form
        action="{{ route('employees.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6"
    >
        @csrf
        {{-- =========================
             DATA IDENTITAS
        ========================== --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-slate-800 mb-5">
                Data Identitas
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Pas Foto --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        No Photo
                    </label>
                    <div class="flex items-center gap-4">
                        <div class="w-24 h-24 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                            No Photo
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">
                                Pas foto dapat diunggah pada bagian Dokumen Karyawan.
                            </p>
                        </div>
                    </div>
                </div>
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Nama Karyawan <span class="text-red-500">\*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Nama lengkap karyawan"
                    >
                </div>
                {{-- No Induk --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        No. Induk Karyawan
                    </label>
                    <input
                        type="text"
                        name="no_induk_karyawan"
                        value="{{ old('no_induk_karyawan') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Nomor induk karyawan"
                    >
                </div>
{{-- Nomor Rekening --}}
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">
        Nomor Rekening
    </label>
    <input
        type="text"
        name="no_rekening"
        value="{{ old('no_rekening') }}"
        maxlength="50"
        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
        placeholder="Masukkan nomor rekening"
    >
    @error('no_rekening')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
                <div>
    <label
        for="fingerprint_pin"
        class="mb-1.5 block text-sm font-medium text-slate-700"
    >
        PIN Fingerprint
    </label>
    <input
        type="text"
        id="fingerprint_pin"
        name="fingerprint_pin"
        value="{{ old('fingerprint_pin', $fingerprintPin ?? request('fingerprint_pin')) }}"
        placeholder="Masukkan PIN fingerprint"
        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
    >
    @error('fingerprint_pin')
        <p class="mt-1 text-xs text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>
                {{-- Jabatan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Jabatan (opsional)
                    </label>
                   <select
                        name="jabatan"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Jabatan</option>
                        @foreach ($jabatanList as $jabatan)
                            <option
                                value="{{ $jabatan }}"
                                {{ old('jabatan') == $jabatan ? 'selected' : '' }}
                            >
                                {{ $jabatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Bagian --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Bagian <span class="text-red-500">\*</span>
                    </label>
                    <select
                        name="bagian"
                        required
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Bagian</option>
                        @foreach ($bagianList as $bagian)
                            <option
                                value="{{ $bagian }}"
                                {{ old('bagian') == $bagian ? 'selected' : '' }}
                            >
                                {{ $bagian }}
                            </option>
                        @endforeach
                    </select>
                </div>
                                    {{-- Unit Penempatan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Unit Penempatan <span class="text-red-500">\*</span>
                    </label>
                    <select
                        name="penempatan"
                        required
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Unit Penempatan</option>
                        @foreach ($penempatanList as $penempatan)
                            <option
                                value="{{ $penempatan }}"
                                {{ old('penempatan') == $penempatan ? 'selected' : '' }}
                            >
                                {{ $penempatan }}
                            </option>
                        @endforeach
                    </select>
                    @error('penempatan')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
       {{-- Jenis Karyawan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Jenis Karyawan
                    </label>
                    <select
                        name="jenis_karyawan"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Jenis</option>
                        <option value="Borongan"
                            {{ old('jenis_karyawan') == 'Borongan' ? 'selected' : '' }}>
                            Borongan
                        </option>
                        <option value="Bulanan"
                            {{ old('jenis_karyawan') == 'Bulanan' ? 'selected' : '' }}>
                            Bulanan
                        </option>
                        <option value="Harian"
                            {{ old('jenis_karyawan') == 'Harian' ? 'selected' : '' }}>
                            Harian
                        </option>
                    </select>
                </div>
                {{-- Pendidikan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Pendidikan
                    </label>
                    <select
                        name="pendidikan"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Pendidikan</option>
                        @foreach ([
                            'SD',
                            'SMP',
                            'SMA/SMK',
                            'D1',
                            'D2',
                            'D3',
                            'D4',
                            'S1',
                            'S2',
                            'S3'
                        ] as $pendidikan)
                            <option
                                value="{{ $pendidikan }}"
                                {{ old('pendidikan') == $pendidikan ? 'selected' : '' }}
                            >
                                {{ $pendidikan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Status Pernikahan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Status Pernikahan
                    </label>
                    <select
                        name="status_pernikahan"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Status</option>
                        <option value="Belum Menikah"
                            {{ old('status_pernikahan') == 'Belum Menikah' ? 'selected' : '' }}>
                            Belum Menikah
                        </option>
                        <option value="Sudah Menikah"
                            {{ old('status_pernikahan') == 'Sudah Menikah' ? 'selected' : '' }}>
                            Sudah Menikah
                        </option>
                    </select>
                </div>
                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Jenis Kelamin
                    </label>
                    <select
                        name="jenis_kelamin"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki"
                            {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>
                        <option value="Perempuan"
                            {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                </div>
                {{-- Tempat Lahir --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Tempat Lahir
                    </label>
                    <input
                        type="text"
                        name="tempat_lahir"
                        value="{{ old('tempat_lahir') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Tempat lahir"
                    >
                </div>
                {{-- Tanggal Lahir --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Tanggal Lahir
                    </label>
                    <input
                        type="date"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                {{-- No KTP --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        No. KTP
                    </label>
                    <input
                        type="text"
                        name="no_ktp"
                        value="{{ old('no_ktp') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Nomor KTP"
                    >
                </div>
               
{{-- NPWP DAN BPJS --}}
<div class="md:col-span-2 rounded-xl border border-slate-200 bg-slate-50 p-5">
    <h3 class="text-lg font-semibold text-slate-800 mb-4">
        NPWP dan BPJS
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- NPWP --}}
        <div>
            <label for="npwp" class="block text-sm font-medium text-slate-700 mb-1">
                NPWP
            </label>
            <input type="text" id="npwp" name="npwp"
                value="{{ old('npwp') }}" maxlength="30"
                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Nomor NPWP (opsional)">
            @error('npwp')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- BPJS KETENAGAKERJAAN --}}
        <div class="md:col-span-2">
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="checkbox" id="bpjs_tk_terdaftar" disabled
                    {{ filled(old('program_bpjs_tk')) && filled(old('no_bpjs_tk')) ? 'checked' : '' }}
                    class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 disabled:opacity-70">
                Terdaftar BPJS Ketenagakerjaan (otomatis)
            </label>

            <div id="bpjs-tk-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                {{-- PROGRAM DI KIRI --}}
                <div>
                    <label for="program_bpjs_tk"
                        class="block text-sm font-medium text-slate-700 mb-1">
                        Program BPJS Ketenagakerjaan
                    </label>
                    <select id="program_bpjs_tk" name="program_bpjs_tk"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Program</option>
                        <option value="Full Program" @selected(old('program_bpjs_tk') === 'Full Program')>
                            Full Program
                        </option>
                        <option value="2 Program" @selected(old('program_bpjs_tk') === '2 Program')>
                            2 Program
                        </option>
                    </select>
                    @error('program_bpjs_tk')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NOMOR DI KANAN --}}
                <div>
                    <label for="no_bpjs_tk"
                        class="block text-sm font-medium text-slate-700 mb-1">
                        Nomor BPJS Ketenagakerjaan
                    </label>
                    <input type="text" id="no_bpjs_tk" name="no_bpjs_tk"
                        value="{{ old('no_bpjs_tk') }}" maxlength="50"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Nomor BPJS TK">
                    @error('no_bpjs_tk')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- BPJS KESEHATAN --}}
        <div class="md:col-span-2 border-t border-slate-200 pt-4">
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="checkbox" id="bpjs_kesehatan_terdaftar" disabled
                    {{ filled(old('kelas_bpjs_kesehatan')) && filled(old('no_bpjs_kesehatan')) ? 'checked' : '' }}
                    class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 disabled:opacity-70">
                Terdaftar BPJS Kesehatan (otomatis)
            </label>

            <div id="bpjs-kesehatan-fields"
                class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                {{-- KELAS DI KIRI --}}
                <div>
                    <label for="kelas_bpjs_kesehatan"
                        class="block text-sm font-medium text-slate-700 mb-1">
                        Kelas BPJS Kesehatan
                    </label>
                    <select id="kelas_bpjs_kesehatan" name="kelas_bpjs_kesehatan"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Kelas</option>
                        <option value="Kelas 1" @selected(old('kelas_bpjs_kesehatan') === 'Kelas 1')>
                            Kelas 1
                        </option>
                        <option value="Kelas 2" @selected(old('kelas_bpjs_kesehatan') === 'Kelas 2')>
                            Kelas 2
                        </option>
                    </select>
                    @error('kelas_bpjs_kesehatan')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NOMOR DI KANAN --}}
                <div>
                    <label for="no_bpjs_kesehatan"
                        class="block text-sm font-medium text-slate-700 mb-1">
                        Nomor BPJS Kesehatan
                    </label>
                    <input type="text" id="no_bpjs_kesehatan"
                        name="no_bpjs_kesehatan"
                        value="{{ old('no_bpjs_kesehatan') }}" maxlength="50"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Nomor BPJS Kesehatan">
                    @error('no_bpjs_kesehatan')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="text-xs text-slate-500 mt-4">
                Status terdaftar akan tercentang otomatis setelah jenis/kelas dan nomor BPJS diisi.
            </p>
        </div>
    </div>
</div>

                        {{-- ALAMAT DOMISILI --}}
<div class="md:col-span-2">
    <label class="block text-sm font-medium text-slate-700 mb-3">
        Alamat Domisili
    </label>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- PROVINSI --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">
                Provinsi
            </label>
            <select
                name="provinsi_id"
                id="provinsi_id"
                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Pilih Provinsi</option>
                @foreach ($provinsiList as $provinsi)
                    <option
                        value="{{ $provinsi->id }}"
                        {{ old('provinsi_id') == $provinsi->id ? 'selected' : '' }}
                    >
                        {{ $provinsi->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- KABUPATEN --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">
                Kabupaten / Kota
            </label>
            <select
                name="kabupaten_id"
                id="kabupaten_id"
                disabled
                class="w-full rounded-lg border-slate-300 bg-slate-50 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Pilih Kabupaten / Kota</option>
            </select>
        </div>
        {{-- KECAMATAN --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">
                Kecamatan
            </label>
            <select
                name="kecamatan_id"
                id="kecamatan_id"
                disabled
                class="w-full rounded-lg border-slate-300 bg-slate-50 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Pilih Kecamatan</option>
            </select>
        </div>
        {{-- DESA --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">
                Desa / Kelurahan
            </label>
            <select
                name="desa_id"
                id="desa_id"
                disabled
                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Pilih Desa / Kelurahan</option>
            </select>
        </div>
        {{-- RT --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">
                RT
            </label>
            <input
                type="text"
                name="rt"
                value="{{ old('rt') }}"
                maxlength="10"
                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Contoh: 001"
            >
        </div>
        {{-- RW --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">
                RW
            </label>
            <input
                type="text"
                name="rw"
                value="{{ old('rw') }}"
                maxlength="10"
                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Contoh: 002"
            >
        </div>
        {{-- DETAIL ALAMAT --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-slate-600 mb-1">
                Detail Alamat
            </label>
            <textarea
                name="detail_alamat"
                rows="3"
                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Nama jalan, dusun, nomor rumah, patokan, dan detail lainnya"
            >{{ old('detail_alamat') }}</textarea>
        </div>
        {{-- =========================
             DATA PEKERJAAN
        ========================== --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-slate-800 mb-5">
                Data Pekerjaan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Mulai Kerja --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Mulai Kerja
                    </label>
                    <input
                        type="date"
                        name="mulai_kerja"
                        value="{{ old('mulai_kerja') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                {{-- Tanggal Kontrak --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Tanggal Kontrak
                    </label>
                    <input
                        type="date"
                        name="tanggal_kontrak"
                        value="{{ old('tanggal_kontrak') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                {{-- Selesai Kontrak --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Selesai Kontrak
                    </label>
                    <input
                        type="date"
                        name="selesai_kontrak"
                        value="{{ old('selesai_kontrak') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                {{-- Kontrak Ke --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Kontrak Ke-
                    </label>
                    <input
                        type="number"
                        name="kontrak_ke"
                        value="{{ old('kontrak_ke', 1) }}"
                        min="1"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                {{-- Jumlah SP --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Jumlah SP
                    </label>
                    <input
                        type="number"
                        name="jumlah_sp"
                        value="{{ old('jumlah_sp', 0) }}"
                        min="0"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Status Karyawan
                    </label>
                    <select
                        name="is_active"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="1"
                            {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="0"
                            {{ old('is_active') == '0' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>
            </div>
        </div>
        {{-- =========================
             DOKUMEN
        ========================== --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-slate-800 mb-2">
                Dokumen Karyawan
            </h3>
            <p class="text-sm text-slate-500 mb-5">
                Upload dokumen dalam format JPG, JPEG, PNG, atau PDF.
                Maksimal 2 MB per file.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Pas Foto --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Pas Foto
                    </label>
                    <input
                        type="file"
                        name="pas_foto"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full text-sm"
                    >
                </div>
                {{-- Foto KK --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Foto KK
                    </label>
                    <input
                        type="file"
                        name="foto_kk"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full text-sm"
                    >
                </div>
                {{-- Foto KTP --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Foto KTP
                    </label>
                    <input
                        type="file"
                        name="foto_ktp"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full text-sm"
                    >
                </div>
                {{-- Foto Biodata --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Foto Biodata
                    </label>
                    <input
                        type="file"
                        name="foto_biodata"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full text-sm"
                    >
                </div>
                {{-- Surat Kontrak --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Surat Kontrak
                    </label>
                    <input
                        type="file"
                        name="surat_kontrak"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full text-sm"
                    >
                </div>
            </div>
        </div>
        {{-- =========================
             BUTTON
        ========================== --}}
        <div class="flex items-center gap-3 pt-5 border-t border-slate-200">
           <a
    href="{{ request()->has('fingerprint_pin')
        ? route('attendance.unregistered')
        : route('employees.index') }}"
    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
\>
    Batal
</a>
            <button
                type="submit"
                class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700"
            >
                Simpan Karyawan
            </button>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const syncBpjsCheckbox = (checkboxId, typeId, numberId) => {
        const checkbox = document.getElementById(checkboxId);
        const type = document.getElementById(typeId);
        const number = document.getElementById(numberId);
        if (!checkbox || !type || !number) return;
        const update = () => {
            checkbox.checked = type.value.trim() !== '' && number.value.trim() !== '';
        };
        type.addEventListener('change', update);
        number.addEventListener('input', update);
        update();
    };
    syncBpjsCheckbox('bpjs_tk_terdaftar', 'program_bpjs_tk', 'no_bpjs_tk');
    syncBpjsCheckbox('bpjs_kesehatan_terdaftar', 'kelas_bpjs_kesehatan', 'no_bpjs_kesehatan');
    const provinsi = document.getElementById('provinsi_id');
    const kabupaten = document.getElementById('kabupaten_id');
    const kecamatan = document.getElementById('kecamatan_id');
    const desa = document.getElementById('desa_id');
    const regionUrl = @json(route('employees.regions'));
    const oldProvinsi = @json(old('provinsi_id'));
    const oldKabupaten = @json(old('kabupaten_id'));
    const oldKecamatan = @json(old('kecamatan_id'));
    const oldDesa = @json(old('desa_id'));
    function resetSelect(*select*, *placeholder*) {
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        select.appendChild(option);
        select.disabled = true;
    }
    function fillSelect(*select*, *regions*, *placeholder*, *selectedId* = null) {
        select.innerHTML = '';
        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.textContent = placeholder;
        select.appendChild(placeholderOption);
        regions.forEach(function (*region*) {
            const option = document.createElement('option');
            option.value = region.id;
            option.textContent = region.nama;
            if (
                selectedId !== null &&
                selectedId !== '' &&
                String(selectedId) === String(region.id)
            ) {
                option.selected = true;
            }
            select.appendChild(option);
        });
        select.disabled = regions.length === 0;
    }
    async function loadRegions(
        *parentId*,
        *level*,
        *select*,
        *placeholder*,
        *selectedId* = null
    ) {
        resetSelect(select, 'Memuat...');
        if (!parentId) {
            resetSelect(select, placeholder);
            return;
        }
        const url =
            regionUrl +
            '?level=' +
            encodeURIComponent(level) +
            '&parent_id=' +
            encodeURIComponent(parentId);
        console.log('Mengambil wilayah:', url);
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            console.log('Status:', response.status);
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            const regions = await response.json();
            console.log('Data ' + level + ':', regions);
            fillSelect(
                select,
                regions,
                placeholder,
                selectedId
            );
        } catch (error) {
            console.error(
                'Gagal mengambil data wilayah:',
                error
            );
            resetSelect(select, placeholder);
        }
    }
    // PROVINSI → KABUPATEN
    provinsi.addEventListener('change', function () {
        resetSelect(
            kabupaten,
            'Pilih Kabupaten / Kota'
        );
        resetSelect(
            kecamatan,
            'Pilih Kecamatan'
        );
        resetSelect(
            desa,
            'Pilih Desa / Kelurahan'
        );
        if (this.value) {
            loadRegions(
                this.value,
                'kabupaten',
                kabupaten,
                'Pilih Kabupaten / Kota'
            );
        }
    });
    // KABUPATEN → KECAMATAN
    kabupaten.addEventListener('change', function () {
        resetSelect(
            kecamatan,
            'Pilih Kecamatan'
        );
        resetSelect(
            desa,
            'Pilih Desa / Kelurahan'
        );
        if (this.value) {
            loadRegions(
                this.value,
                'kecamatan',
                kecamatan,
                'Pilih Kecamatan'
            );
        }
    });
    // KECAMATAN → DESA
    kecamatan.addEventListener('change', function () {
        resetSelect(
            desa,
            'Pilih Desa / Kelurahan'
        );
        if (this.value) {
            loadRegions(
                this.value,
                'desa',
                desa,
                'Pilih Desa / Kelurahan'
            );
        }
    });
    // RESTORE OLD INPUT
    async function restoreOldAddress() {
        if (!oldProvinsi) {
            return;
        }
        provinsi.value = oldProvinsi;
        await loadRegions(
            oldProvinsi,
            'kabupaten',
            kabupaten,
            'Pilih Kabupaten / Kota',
            oldKabupaten
        );
        if (!oldKabupaten) {
            return;
        }
        await loadRegions(
            oldKabupaten,
            'kecamatan',
            kecamatan,
            'Pilih Kecamatan',
            oldKecamatan
        );
        if (!oldKecamatan) {
            return;
        }
        await loadRegions(
            oldKecamatan,
            'desa',
            desa,
            'Pilih Desa / Kelurahan',
            oldDesa
        );
    }
    restoreOldAddress();
});
</script>
@endsection
