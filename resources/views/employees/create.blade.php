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
                        Nama Karyawan <span class="text-red-500">*</span>
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

                {{-- PIN Fingerprint --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        PIN Fingerprint
                    </label>

                    <input
                        type="text"
                        name="fingerprint_pin"
                        value="{{ old('fingerprint_pin') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="PIN fingerprint"
                    >
                </div>

                {{-- Jabatan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Jabatan
                    </label>

                    <input
                        type="text"
                        name="jabatan"
                        value="{{ old('jabatan') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Jabatan"
                    >
                </div>

                {{-- Bagian --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Bagian <span class="text-red-500">*</span>
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

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Alamat Domisili
                    </label>

                    <textarea
                        name="alamat_domisili"
                        rows="3"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Alamat domisili"
                    >{{ old('alamat_domisili') }}</textarea>
                </div>

            </div>
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
                href="{{ route('employees.index') }}"
                class="px-5 py-2.5 rounded-lg bg-slate-200 text-slate-700 font-medium hover:bg-slate-300"
            >
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

@endsection