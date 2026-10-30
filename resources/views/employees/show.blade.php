@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="soft-card p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <span class="mini-tag">
                    Data Karyawan
                </span>

                <h1 class="mt-3 text-3xl font-bold text-slate-800">
                    Detail Karyawan
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Informasi lengkap data karyawan PT Starfood International.
                </p>
            </div>

            <div class="flex gap-2">

                <a
                    href="{{ route('employees.edit', $employee) }}"
                    class="primary-btn"
                >
                    Edit Data
                </a>

                <a
                    href="{{ route('employees.index') }}"
                    class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-200"
                >
                    Kembali
                </a>

            </div>

        </div>
    </div>


    {{-- IDENTITAS --}}
    <div class="soft-card p-6">

        <h2 class="text-lg font-semibold text-slate-800 mb-5">
            Data Identitas
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- FOTO --}}
            <div class="md:row-span-2">

                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">
                    Pas Foto
                </p>

                @if($employee->pas_foto)

                    <img
                        src="{{ asset('storage/' . $employee->pas_foto) }}"
                        alt="{{ $employee->name }}"
                        class="w-40 h-48 rounded-xl object-cover border border-slate-200"
                    >

                @else

                    <div class="w-40 h-48 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-sm text-slate-400">
                        No Photo
                    </div>

                @endif

            </div>


            {{-- NAMA --}}
            <div>
                <p class="text-xs text-slate-500">
                    Nama Karyawan
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->name ?? '-' }}
                </p>
            </div>


            {{-- NO INDUK --}}
            <div>
                <p class="text-xs text-slate-500">
                    No. Induk Karyawan
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->no_induk_karyawan ?? '-' }}
                </p>
            </div>


            {{-- PIN --}}
            <div>
                <p class="text-xs text-slate-500">
                    PIN Fingerprint
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->fingerprint_pin ?? '-' }}
                </p>
            </div>
<div class="detail-item">
    <div class="detail-label">Nomor Rekening</div>
    <div class="detail-value">
        {{ $employee->no_rekening ?: '-' }}
    </div>
</div>

            {{-- JABATAN --}}
            <div>
                <p class="text-xs text-slate-500">
                    Jabatan
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->jabatan ?? '-' }}
                </p>
            </div>


            {{-- BAGIAN --}}
            <div>
                <p class="text-xs text-slate-500">
                    Bagian
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->bagian ?? '-' }}
                </p>
            </div>


            {{-- JENIS --}}
            <div>
                <p class="text-xs text-slate-500">
                    Jenis Karyawan
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->jenis_karyawan ?? '-' }}
                </p>
            </div>


            {{-- PENDIDIKAN --}}
            <div>
                <p class="text-xs text-slate-500">
                    Pendidikan
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->pendidikan ?? '-' }}
                </p>
            </div>


            {{-- STATUS PERNIKAHAN --}}
            <div>
                <p class="text-xs text-slate-500">
                    Status Pernikahan
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->status_pernikahan ?? '-' }}
                </p>
            </div>


            {{-- JENIS KELAMIN --}}
            <div>
                <p class="text-xs text-slate-500">
                    Jenis Kelamin
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->jenis_kelamin ?? '-' }}
                </p>
            </div>


            {{-- TEMPAT LAHIR --}}
            <div>
                <p class="text-xs text-slate-500">
                    Tempat Lahir
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->tempat_lahir ?? '-' }}
                </p>
            </div>


            {{-- TANGGAL LAHIR --}}
            <div>
                <p class="text-xs text-slate-500">
                    Tanggal Lahir
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->tanggal_lahir ? $employee->tanggal_lahir->format('d-m-Y') : '-' }}
                </p>
            </div>


            {{-- UMUR --}}
            <div>
                <p class="text-xs text-slate-500">
                    Umur
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->tanggal_lahir ? $employee->tanggal_lahir->age . ' tahun' : '-' }}
                </p>
            </div>


            {{-- KTP --}}
            <div>
                <p class="text-xs text-slate-500">
                    No. KTP
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->no_ktp ?? '-' }}
                </p>
            </div>


            {{-- ALAMAT --}}
            <div class="md:col-span-2">

                <p class="text-xs text-slate-500">
                    Alamat Domisili
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->alamat_domisili ?? '-' }}
                </p>

            </div>

        </div>

    </div>



{{-- NPWP DAN BPJS --}}
<div class="soft-card p-6">
    <h2 class="text-lg font-semibold text-slate-800 mb-5">
        Data Pajak dan BPJS
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- NPWP --}}
        <div>
            <p class="text-xs text-slate-500">NPWP</p>
            <p class="mt-1 font-medium text-slate-800">
                {{ $employee->npwp ?: '-' }}
            </p>
        </div>

        {{-- STATUS BPJS TK --}}
        <div>
            <p class="text-xs text-slate-500">Status BPJS Ketenagakerjaan</p>
            <p class="mt-1">
                @if ($employee->bpjs_tk_terdaftar)
                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                        Terdaftar
                    </span>
                @else
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                        Tidak Terdaftar
                    </span>
                @endif
            </p>
        </div>

        {{-- PROGRAM BPJS TK --}}
        <div>
            <p class="text-xs text-slate-500">Program BPJS Ketenagakerjaan</p>
            <p class="mt-1 font-medium text-slate-800">
                {{ $employee->bpjs_tk_terdaftar ? ($employee->program_bpjs_tk ?: '-') : 'Tidak Terdaftar' }}
            </p>
        </div>

        {{-- NOMOR BPJS TK --}}
        <div>
            <p class="text-xs text-slate-500">Nomor BPJS Ketenagakerjaan</p>
            <p class="mt-1 font-medium text-slate-800">
                {{ $employee->bpjs_tk_terdaftar ? ($employee->no_bpjs_tk ?: '-') : 'Tidak Terdaftar' }}
            </p>
        </div>

        {{-- STATUS BPJS KESEHATAN --}}
        <div>
            <p class="text-xs text-slate-500">Status BPJS Kesehatan</p>
            <p class="mt-1">
                @if ($employee->bpjs_kesehatan_terdaftar)
                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                        Terdaftar
                    </span>
                @else
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                        Tidak Terdaftar
                    </span>
                @endif
            </p>
        </div>

        {{-- KELAS BPJS KESEHATAN --}}
        <div>
            <p class="text-xs text-slate-500">Kelas BPJS Kesehatan</p>
            <p class="mt-1 font-medium text-slate-800">
                {{ $employee->bpjs_kesehatan_terdaftar ? ($employee->kelas_bpjs_kesehatan ?: '-') : 'Tidak Terdaftar' }}
            </p>
        </div>

        {{-- NOMOR BPJS KESEHATAN --}}
        <div>
            <p class="text-xs text-slate-500">Nomor BPJS Kesehatan</p>
            <p class="mt-1 font-medium text-slate-800">
                {{ $employee->bpjs_kesehatan_terdaftar ? ($employee->no_bpjs_kesehatan ?: '-') : 'Tidak Terdaftar' }}
            </p>
        </div>

    </div>
</div>


    {{-- DATA PEKERJAAN --}}
    <div class="soft-card p-6">

        <h2 class="text-lg font-semibold text-slate-800 mb-5">
            Data Pekerjaan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>
                <p class="text-xs text-slate-500">
                    Mulai Kerja
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->mulai_kerja ? $employee->mulai_kerja->format('d-m-Y') : '-' }}
                </p>
            </div>


            <div>
                <p class="text-xs text-slate-500">
                    Tanggal Kontrak
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->tanggal_kontrak ? $employee->tanggal_kontrak->format('d-m-Y') : '-' }}
                </p>
            </div>


            <div>
                <p class="text-xs text-slate-500">
                    Selesai Kontrak
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->selesai_kontrak ? $employee->selesai_kontrak->format('d-m-Y') : '-' }}
                </p>
            </div>


            <div>
                <p class="text-xs text-slate-500">
                    Kontrak Ke-
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->kontrak_ke ?? '-' }}
                </p>
            </div>


            <div>
                <p class="text-xs text-slate-500">
                    Jumlah SP
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $employee->jumlah_sp ?? 0 }}
                </p>
            </div>


            <div>
                <p class="text-xs text-slate-500">
                    Status Karyawan
                </p>

                <p class="mt-1">

                    @if($employee->is_active)

                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                            Aktif
                        </span>

                    @else

                        <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-medium text-slate-700">
                            Nonaktif
                        </span>

                    @endif

                </p>
            </div>

        </div>

    </div>

    
{{-- RIWAYAT KONTRAK --}}
<div class="soft-card p-6">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Riwayat Kontrak</h2>
            <p class="mt-1 text-sm text-slate-500">Riwayat kontrak kerja karyawan.</p>
        </div>

        <a href="{{ route('contracts.create') }}"
           class="primary-btn">
            + Tambah Kontrak
        </a>
    </div>

    @if ($employee->contracts->isNotEmpty())
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Kontrak Ke-</th>
                        <th class="px-4 py-3">Nomor Kontrak</th>
                        <th class="px-4 py-3">Jenis Kontrak</th>
                        <th class="px-4 py-3">Mulai</th>
                        <th class="px-4 py-3">Berakhir</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Dokumen & Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($employee->contracts as $contract)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-slate-700">
                                {{ $contract->kontrak_ke ? 'Kontrak ke-' . $contract->kontrak_ke : '-' }}
                            </td>
                            <td class="px-4 py-3 text-slate-700">{{ $contract->nomor_kontrak ?: '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $contract->jenis_kontrak ?: '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $contract->tanggal_mulai?->format('d-m-Y') ?? '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $contract->tanggal_berakhir?->format('d-m-Y') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ strtolower($contract->status ?? '') === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($contract->status ?? '-') }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                @if ($contract->file_surat)
                                    <a href="{{ asset('storage/' . $contract->file_surat) }}" target="_blank" rel="noopener noreferrer" class="font-medium text-blue-700 hover:underline">Lihat Surat</a>
                                @else
                                    <span class="text-slate-400">Belum diunggah</span>
                                @endif
                                <a href="{{ route('contracts.print', $contract) }}" target="_blank" rel="noopener noreferrer" class="ml-2 font-medium text-emerald-700 hover:underline">Cetak PDF</a>
                                <a href="{{ route('contracts.show', $contract) }}" class="ml-2 font-medium text-blue-700 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rounded-xl border border-dashed border-slate-300 px-6 py-8 text-center">
            <p class="font-medium text-slate-600">Belum ada riwayat kontrak.</p>
            <p class="mt-1 text-sm text-slate-500">Tambahkan kontrak melalui tombol di atas.</p>
        </div>
    @endif
</div>

{{-- RIWAYAT SURAT TEGURAN --}}
<div class="soft-card p-6">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                Riwayat Surat Teguran
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Riwayat Surat Teguran karyawan.
            </p>
        </div>

        <a href="{{ route('warning-notices.create', ['employee_id' => $employee->id]) }}"
           class="primary-btn">
            + Tambah Surat Teguran
        </a>
    </div>

    @if ($employee->warningNotices->isNotEmpty())
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Nomor Surat</th>
                        <th class="px-4 py-3">Tanggal Surat</th>
                        <th class="px-4 py-3">Mulai Berlaku</th>
                        <th class="px-4 py-3">Akhir Berlaku</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach ($employee->warningNotices as $notice)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800">
                                {{ $notice->nomor_surat ?: '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ $notice->tanggal_surat?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ $notice->tanggal_mulai_berlaku?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ $notice->tanggal_akhir_berlaku?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                <a href="{{ route('warning-notices.show', $notice) . '?return_to=' . urlencode(route('employees.show', $employee)) }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Detail
                                </a>

                                <a href="{{ route('warning-notices.print', $notice) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="ml-3 font-medium text-emerald-700 hover:underline">
                                    Unduh PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rounded-xl border border-dashed border-slate-300 px-6 py-8 text-center">
            <p class="font-medium text-slate-600">
                Belum ada riwayat Surat Teguran.
            </p>
            <p class="mt-1 text-sm text-slate-500">
                Tambahkan Surat Teguran melalui tombol di atas.
            </p>
        </div>
    @endif
</div>


{{-- RIWAYAT SURAT PERINGATAN --}}
<div class="soft-card p-6">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                Riwayat Surat Peringatan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Riwayat Surat Peringatan (SP) karyawan.
            </p>
        </div>

        <a href="{{ route('warning-letters.create', ['employee_id' => $employee->id]) }}"
           class="primary-btn">
            + Tambah Surat Peringatan
        </a>
    </div>

    @if ($employee->warningLetters->isNotEmpty())
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Nomor Surat</th>
                        <th class="px-4 py-3">Tingkat SP</th>
                        <th class="px-4 py-3">Tanggal Surat</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach ($employee->warningLetters as $letter)
                        @php
                            $tingkatSp = (int) $letter->tingkat_sp;
                            $labelSp = [
                                1 => 'SP I',
                                2 => 'SP II',
                                3 => 'SP III',
                            ][$tingkatSp] ?? ($tingkatSp ? 'SP ' . $tingkatSp : '-');
                        @endphp

                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800">
                                {{ $letter->nomor_surat ?: '-' }}
                            </td>

                            <td class="px-4 py-3 text-slate-700">
                                {{ $labelSp }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ $letter->tanggal_surat?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ strtolower($letter->status ?? '') === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($letter->status ?? '-') }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                <a href="{{ route('warning-letters.show', $letter) . '?return_to=' . urlencode(route('employees.show', $employee)) }}"
                                   class="font-medium text-blue-700 hover:underline">
                                    Detail
                                </a>

                                <a href="{{ route('warning-letters.print', $letter) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="ml-3 font-medium text-emerald-700 hover:underline">
                                    Unduh PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rounded-xl border border-dashed border-slate-300 px-6 py-8 text-center">
            <p class="font-medium text-slate-600">
                Belum ada riwayat Surat Peringatan.
            </p>
            <p class="mt-1 text-sm text-slate-500">
                Tambahkan Surat Peringatan melalui tombol di atas.
            </p>
        </div>
    @endif
</div>

{{-- DOKUMEN --}}
    <div class="soft-card p-6">

        <h2 class="text-lg font-semibold text-slate-800 mb-2">
            Dokumen Karyawan
        </h2>

        <p class="text-sm text-slate-500 mb-5">
            Dokumen yang telah diunggah untuk karyawan.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- PAS FOTO --}}
            <div class="border border-slate-200 rounded-xl p-4">

                <p class="font-medium text-slate-800">
                    Pas Foto
                </p>

                @if($employee->pas_foto)

                    <a
                        href="{{ asset('storage/' . $employee->pas_foto) }}"
                        target="_blank"
                        class="inline-block mt-3 text-sm text-blue-600 hover:underline"
                    >
                        Lihat Dokumen
                    </a>

                @else

                    <p class="mt-2 text-sm text-slate-400">
                        Belum tersedia
                    </p>

                @endif

            </div>


            {{-- KK --}}
            <div class="border border-slate-200 rounded-xl p-4">

                <p class="font-medium text-slate-800">
                    Foto KK
                </p>

                @if($employee->foto_kk)

                    <a
                        href="{{ asset('storage/' . $employee->foto_kk) }}"
                        target="_blank"
                        class="inline-block mt-3 text-sm text-blue-600 hover:underline"
                    >
                        Lihat Dokumen
                    </a>

                @else

                    <p class="mt-2 text-sm text-slate-400">
                        Belum tersedia
                    </p>

                @endif

            </div>


            {{-- KTP --}}
            <div class="border border-slate-200 rounded-xl p-4">

                <p class="font-medium text-slate-800">
                    Foto KTP
                </p>

                @if($employee->foto_ktp)

                    <a
                        href="{{ asset('storage/' . $employee->foto_ktp) }}"
                        target="_blank"
                        class="inline-block mt-3 text-sm text-blue-600 hover:underline"
                    >
                        Lihat Dokumen
                    </a>

                @else

                    <p class="mt-2 text-sm text-slate-400">
                        Belum tersedia
                    </p>

                @endif

            </div>


            {{-- BIODATA --}}
            <div class="border border-slate-200 rounded-xl p-4">

                <p class="font-medium text-slate-800">
                    Foto Biodata
                </p>

                @if($employee->foto_biodata)

                    <a
                        href="{{ asset('storage/' . $employee->foto_biodata) }}"
                        target="_blank"
                        class="inline-block mt-3 text-sm text-blue-600 hover:underline"
                    >
                        Lihat Dokumen
                    </a>

                @else

                    <p class="mt-2 text-sm text-slate-400">
                        Belum tersedia
                    </p>

                @endif

            </div>


            {{-- SURAT KONTRAK --}}
            <div class="border border-slate-200 rounded-xl p-4 md:col-span-2">

                <p class="font-medium text-slate-800">
                    Surat Kontrak
                </p>

                @if($employee->surat_kontrak)

                    <a
                        href="{{ asset('storage/' . $employee->surat_kontrak) }}"
                        target="_blank"
                        class="inline-block mt-3 text-sm text-blue-600 hover:underline"
                    >
                        Lihat Dokumen
                    </a>

                @else

                    <p class="mt-2 text-sm text-slate-400">
                        Belum tersedia
                    </p>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection