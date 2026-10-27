@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div>
        <p class="text-sm font-medium text-blue-600">
            GAJI KARYAWAN
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-800">
            Data Absensi
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Data absensi dari mesin fingerprint, Excel, dan file DAT
            yang tersimpan di sistem.
        </p>
    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3">
            <p class="text-sm font-medium text-green-700">
                {{ session('success') }}
            </p>
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">

            <p class="mb-2 text-sm font-semibold text-red-700">
                Terjadi kesalahan:
            </p>

            <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATISTIK --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        {{-- TOTAL SCAN --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Total Scan
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ number_format($totalScan) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Seluruh data absensi
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 11V7m0 8h.01M5.5 20h13a1.5 1.5 0 001.5-1.5v-13A1.5 1.5 0 0018.5 4h-13A1.5 1.5 0 004 5.5v13A1.5 1.5 0 005.5 20z"
                        />
                    </svg>

                </div>

            </div>

        </div>


        {{-- SCAN HARI INI --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Scan Hari Ini
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ number_format($scanHariIni) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Scan pada hari ini
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>

                </div>

            </div>

        </div>


        {{-- TOTAL TERDAFTAR --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Scan Terdaftar
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ number_format($totalTerdaftar) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Terhubung dengan Data Karyawan
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                </div>

            </div>

        </div>


        {{-- PIN BELUM TERDAFTAR --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        PIN Belum Terdaftar
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ number_format($totalBelumTerdaftar) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Belum ditemukan di Data Karyawan
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v4m0 4h.01M10.3 3.7l-7.6 13.2A2 2 0 004.4 20h15.2a2 2 0 001.7-3.1L13.7 3.7a2 2 0 00-3.4 0z"
                        />
                    </svg>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PIN BELUM TERDAFTAR --}}
    {{-- ========================================================= --}}

    @if($unregisteredPins->count() > 0)

        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>

                    <p class="font-semibold text-amber-800">
                        Ada PIN yang belum terdaftar
                    </p>

                    <p class="mt-1 text-sm text-amber-700">
                        Terdapat
                        <span class="font-semibold">
                            {{ $unregisteredPins->count() }}
                        </span>
                        PIN yang belum ditemukan pada Data Karyawan.
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">

                        @foreach($unregisteredPins as $pin)

                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-amber-800 ring-1 ring-amber-200">
                                {{ $pin }}
                            </span>

                        @endforeach

                    </div>

                </div>

                <a
                    href="{{ route('attendance.unregistered') }}"
                    class="inline-flex shrink-0 items-center justify-center rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700"
                >
                    Lihat PIN
                </a>

            </div>

        </div>

    @endif


    {{{-- ========================================================= --}}
{{-- IMPORT DATA --}}
{{-- ========================================================= --}}

<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="mb-5">
        <h2 class="text-base font-semibold text-slate-800">
            Import Data Absensi
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Masukkan data absensi dari file Excel atau hasil export mesin fingerprint.
        </p>
    </div>


    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

        {{-- ================================================= --}}
        {{-- IMPORT EXCEL --}}
        {{-- ================================================= --}}

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

            <div class="mb-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 3v5h5"
                            />
                        </svg>

                    </div>

                    <div>

                        <h3 class="font-semibold text-slate-800">
                            Import Excel
                        </h3>

                        <p class="text-xs text-slate-500">
                            File .xlsx, .xls, atau .csv
                        </p>

                    </div>

                </div>

            </div>


            <form
                action="{{ route('attendance.import.excel') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="flex flex-col gap-3">

                    <input
                        type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="block w-full rounded-lg border border-slate-300
                               bg-white text-sm text-slate-600
                               file:mr-3
                               file:border-0
                               file:bg-slate-100
                               file:px-4
                               file:py-2.5
                               file:text-sm
                               file:font-medium
                               file:text-slate-700
                               hover:file:bg-slate-200"
                    >

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center
                               rounded-lg bg-blue-600 px-4 py-2.5
                               text-sm font-semibold text-white
                               transition hover:bg-blue-700"
                    >
                        Import Excel
                    </button>

                </div>

            </form>

        </div>


        {{-- ================================================= --}}
        {{-- IMPORT DAT --}}
        {{-- ================================================= --}}

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

            <div class="mb-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 3v5h5"
                            />
                        </svg>

                    </div>

                    <div>

                        <h3 class="font-semibold text-slate-800">
                            Import DAT
                        </h3>

                        <p class="text-xs text-slate-500">
                            File hasil export mesin fingerprint
                        </p>

                    </div>

                </div>

            </div>


            <form
                action="{{ route('attendance.import.dat') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="flex flex-col gap-3">

                    <input
                        type="file"
                        name="file"
                        accept=".dat"
                        required
                        class="block w-full rounded-lg border border-slate-300
                               bg-white text-sm text-slate-600
                               file:mr-3
                               file:border-0
                               file:bg-slate-100
                               file:px-4
                               file:py-2.5
                               file:text-sm
                               file:font-medium
                               file:text-slate-700
                               hover:file:bg-slate-200"
                    >

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center
                               rounded-lg bg-purple-600 px-4 py-2.5
                               text-sm font-semibold text-white
                               transition hover:bg-purple-700"
                    >
                        Import DAT
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5">

            <h2 class="text-base font-semibold text-slate-800">
                Filter Data
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Cari dan filter data absensi berdasarkan periode,
                sumber data, PIN, atau nama karyawan.
            </p>

        </div>


        <form
            method="GET"
            action="{{ route('attendance.index') }}"
        >

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                {{-- TANGGAL MULAI --}}
                <div>

                    <label
                        for="tanggal_mulai"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        id="tanggal_mulai"
                        name="tanggal_mulai"
                        value="{{ request('tanggal_mulai') }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- TANGGAL SELESAI --}}
                <div>

                    <label
                        for="tanggal_selesai"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Tanggal Selesai
                    </label>

                    <input
                        type="date"
                        id="tanggal_selesai"
                        name="tanggal_selesai"
                        value="{{ request('tanggal_selesai') }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- SOURCE --}}
                <div>

                    <label
                        for="source"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Source
                    </label>

                    <select
                        id="source"
                        name="source"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            Semua Source
                        </option>

                        <option
                            value="excel"
                            @selected(request('source') === 'excel')
                        >
                            Excel
                        </option>

                        <option
                            value="dat"
                            @selected(request('source') === 'dat')
                        >
                            DAT
                        </option>

                        <option
                            value="adms"
                            @selected(request('source') === 'adms')
                        >
                            Fingerprint
                        </option>

                    </select>

                </div>


                {{-- SEARCH --}}
                <div>

                    <label
                        for="search"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Cari
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="PIN atau nama..."
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>

            </div>


            {{-- BUTTON --}}
            <div class="mt-5 flex gap-2">

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Terapkan Filter
                </button>

                <a
                    href="{{ route('attendance.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- DATA TABLE --}}
    {{-- ========================================================= --}}

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-base font-semibold text-slate-800">
                        Data Absensi
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Menampilkan data scan fingerprint yang tersimpan.
                    </p>

                </div>

                <div class="text-sm text-slate-500">

                    Total:
                    <span class="font-semibold text-slate-700">
                        {{ number_format($attendanceLogs->total()) }}
                    </span>
                    data

                </div>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-50">

                    <tr class="border-b border-slate-200">

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-700">
                            PIN
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-700">
                            Nama
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-700">
                            Departemen
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-700">
                            Jenis Karyawan
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-700">
                            Tanggal
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-700">
                            Jam
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-left font-semibold text-slate-700">
                            Source
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($attendanceLogs as $attendance)

                        <tr class="transition hover:bg-slate-50">

                            {{-- PIN --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <span class="font-semibold text-slate-800">
                                    {{ $attendance->pin }}
                                </span>
                            </td>


                            {{-- NAMA --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                @if($attendance->employee)

                                    <span class="font-medium text-slate-700">
                                        {{ $attendance->employee->name }}
                                    </span>

                                @else

                                    <span class="text-red-500">
                                        Belum terdaftar
                                    </span>

                                @endif

                            </td>


                            {{-- DEPARTEMEN --}}
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">

                                {{ $attendance->employee?->bagian ?? '-' }}

                            </td>


                            {{-- JENIS KARYAWAN --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                @if($attendance->employee?->jenis_karyawan)

                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                        {{ $attendance->employee->jenis_karyawan }}
                                    </span>

                                @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- TANGGAL --}}
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">

                                {{ $attendance->punched_at?->format('d/m/Y') ?? '-' }}

                            </td>


                            {{-- JAM --}}
                            <td class="whitespace-nowrap px-6 py-4 font-medium text-slate-700">

                                {{ $attendance->punched_at?->format('H:i:s') ?? '-' }}

                            </td>


                            {{-- SOURCE --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                @if($attendance->source === 'excel')

                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                        Excel
                                    </span>

                                @elseif($attendance->source === 'dat')

                                    <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-medium text-purple-700">
                                        DAT
                                    </span>

                                @elseif($attendance->source === 'adms')

                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                                        Fingerprint
                                    </span>

                                @else

                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                        {{ $attendance->source ?? '-' }}
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-16 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"
                                            />
                                        </svg>

                                    </div>

                                    <p class="mt-3 font-medium text-slate-700">
                                        Belum ada data absensi
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Data fingerprint akan muncul di sini.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($attendanceLogs->hasPages())

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $attendanceLogs->links() }}
            </div>

        @endif

    </div>

</div>

@endsection