@extends('layouts.app')

@section('title', 'Hitung Gaji')

@section('content')

@php
    $jamBulat = fn ($value) => app(
        \App\Services\SalaryCalculationService::class
    )->roundHoursToWhole((float) ($value ?? 0));
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <p class="text-sm font-medium text-blue-600">
            GAJI KARYAWAN
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-800">
            Hitung Gaji
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Hitung gaji karyawan borongan berdasarkan data fingerprint.
        </p>
    </div>


    {{-- ============================= --}}
    {{-- IMPORT DATA FINGERPRINT --}}
    {{-- ============================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Import Data Fingerprint
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Masukkan periode dan file fingerprint untuk memuat data checklog.
            </p>
        </div>

        <form
            action="{{ route('checklogs.import') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                {{-- TANGGAL MULAI --}}
                <div>
                    <label
                        for="tanggal_mulai"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        id="tanggal_mulai"
                        name="tanggal_mulai"
                        value="{{ old('tanggal_mulai') }}"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                {{-- TANGGAL SELESAI --}}
                <div>
                    <label
                        for="tanggal_selesai"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Tanggal Selesai
                    </label>

                    <input
                        type="date"
                        id="tanggal_selesai"
                        name="tanggal_selesai"
                        value="{{ old('tanggal_selesai') }}"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                {{-- FILE --}}
                <div>
                    <label
                        for="file"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        File Fingerprint
                    </label>

                    <input
                        type="file"
                        id="file"
                        name="file"
                        accept=".xlsx,.xls"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-600 outline-none transition file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                {{-- BUTTON --}}
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                        Import Excel
                    </button>
                </div>

            </div>

        </form>

    </div>


    {{-- ============================= --}}
    {{-- FILTER DATA --}}
    {{-- ============================= --}}

    @if($checklogs->count())

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Filter Data
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Gunakan filter untuk menampilkan data checklog tertentu.
            </p>
        </div>

        <form
            action="{{ route('checklogs.index') }}"
            method="GET"
        >

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">

                {{-- DARI TANGGAL --}}
                <div>
                    <label
                        for="filter_tanggal_mulai"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Dari Tanggal
                    </label>

                    <input
                        type="date"
                        id="filter_tanggal_mulai"
                        name="filter_tanggal_mulai"
                        value="{{ request('filter_tanggal_mulai') }}"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                {{-- SAMPAI TANGGAL --}}
                <div>
                    <label
                        for="filter_tanggal_selesai"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        id="filter_tanggal_selesai"
                        name="filter_tanggal_selesai"
                        value="{{ request('filter_tanggal_selesai') }}"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                {{-- NAMA --}}
                <div class="md:col-span-2 xl:col-span-1">
                    <label
                        for="nama_karyawan"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Nama Karyawan
                    </label>

                    <input
                        type="text"
                        id="nama_karyawan"
                        name="nama_karyawan"
                        value="{{ request('nama_karyawan') }}"
                        placeholder="Cari nama karyawan..."
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                {{-- CARI --}}
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                        Cari
                    </button>
                </div>


                {{-- RESET --}}
                <div class="flex items-end">
                    <a
                        href="{{ route('checklogs.index') }}"
                        class="flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                    >
                        Reset
                    </a>
                </div>

            </div>

        </form>

    </div>

    @endif


    {{-- ============================= --}}
    {{-- DATA CHECKLOG --}}
    {{-- ============================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- HEADER TABLE --}}
        <div class="flex flex-col gap-3 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Data Checklog
                </h2>

                @if($tanggalMulai && $tanggalSelesai)

                    <p class="mt-1 text-sm text-slate-500">

                        Periode:

                        <span class="font-medium text-slate-700">
                            {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }}
                        </span>

                        <span class="mx-1">
                            s/d
                        </span>

                        <span class="font-medium text-slate-700">
                            {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}
                        </span>

                    </p>

                @endif

            </div>


            @if($checklogs->count())

                <div class="rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-500">

                    Menampilkan

                    <span class="font-semibold text-slate-700">
                        {{ $checklogs->count() }}
                    </span>

                    data

                </div>

            @endif

        </div>


        @if($checklogs->count())

            {{-- ============================= --}}
            {{-- FORM HITUNG SEMUA --}}
            {{-- ============================= --}}

            <form
                action="{{ route('checklogs.hitungSemua') }}"
                method="POST"
            >

                @csrf

                {{-- TABLE SCROLL --}}
                <div class="w-full overflow-x-auto">

                    <table class="min-w-[1200px] w-full text-left text-sm">

                        <thead class="border-b border-slate-200 bg-slate-50">

                            <tr>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    No
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    No. ID
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    Nama Karyawan
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    Tanggal
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    Jam Masuk
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    Jam Pulang
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    Scan Masuk
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    Scan Pulang
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    Istirahat
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    Total Jam Kerja
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-600">
                                    Gaji
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach($checklogs as $checklog)

                                <tr class="transition hover:bg-slate-50">

                                    <td class="whitespace-nowrap px-4 py-3 text-slate-500">
                                        {{ $loop->iteration }}
                                    </td>


                                    <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-700">
                                        {{ $checklog->employee->no_id }}
                                    </td>


                                    <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-800">
                                        {{ $checklog->employee->name }}
                                    </td>


                                    <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                        {{ $checklog->tanggal->format('d-m-Y') }}
                                    </td>


                                    <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                        {{ $checklog->jam_masuk ?? '-' }}
                                    </td>


                                    <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                        {{ $checklog->jam_pulang ?? '-' }}
                                    </td>


                                    <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                        {{ $checklog->scan_masuk ?? '-' }}
                                    </td>


                                    <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                        {{ $checklog->scan_pulang ?? '-' }}
                                    </td>


                                    <td class="px-4 py-3">

                                        <input
                                            type="text"
                                            name="istirahat[{{ $checklog->id }}]"
                                            value="{{ $checklog->jam_istirahat ?? '0.00' }}"
                                            placeholder="0.00"
                                            class="w-20 rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-center text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        >

                                    </td>


                                    <td class="whitespace-nowrap px-4 py-3 text-slate-700">

                                        {{ number_format($jamBulat($checklog->total_jam_kerja), 0, ',', '.') }}

                                        jam

                                    </td>


                                    <td class="whitespace-nowrap px-4 py-3 font-semibold text-slate-800">

                                        Rp

                                        {{ number_format(
                                            (float) $checklog->gaji,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- TOMBOL HITUNG --}}
                <div class="flex flex-col gap-3 border-t border-slate-200 p-5 sm:flex-row">

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                    >
                        Hitung Semua
                    </button>

                </div>

            </form>


            {{-- ============================= --}}
            {{-- SIMPAN ARSIP --}}
            {{-- ============================= --}}

            @if($tanggalMulai && $tanggalSelesai)

                <form
                    action="{{ route('checklogs.simpanArsip') }}"
                    method="POST"
                    class="border-t border-slate-200"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="tanggal_mulai"
                        value="{{ \Carbon\Carbon::parse($tanggalMulai)->format('Y-m-d') }}"
                    >

                    <input
                        type="hidden"
                        name="tanggal_selesai"
                        value="{{ \Carbon\Carbon::parse($tanggalSelesai)->format('Y-m-d') }}"
                    >

                    <div class="p-5">

                        <button
                            type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            onclick="return confirm('Simpan semua data gaji pada periode ini ke arsip?')"
                        >
                            Simpan Arsip Gaji
                        </button>

                    </div>

                </form>

            @endif


        @else

            {{-- DATA KOSONG --}}
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">

                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M20 13V7a2 2 0 00-2-2h-3.5l-1-1h-3l-1 1H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4"
                        />
                    </svg>

                </div>

                <h3 class="mt-4 text-base font-semibold text-slate-700">
                    Data tidak ditemukan
                </h3>

                <p class="mt-1 max-w-md text-sm text-slate-500">
                    Silakan ubah filter atau import file fingerprint terlebih dahulu.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection