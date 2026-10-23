@extends('layouts.app')

@section('title', 'Data Fingerprint')

@section('content')

<div class="space-y-6">

    {{-- ============================= --}}
    {{-- HEADER --}}
    {{-- ============================= --}}

    <div>
        <p class="text-sm font-medium text-blue-600">
            GAJI KARYAWAN
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-800">
            Data Fingerprint
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Data absensi yang diterima dari mesin fingerprint PT Starfood International.
        </p>
    </div>


    {{-- ============================= --}}
    {{-- PERINGATAN PIN BELUM TERDAFTAR --}}
    {{-- ============================= --}}

    @if($belumTerdaftar > 0)

        <div class="rounded-2xl border border-red-200 bg-red-50 p-5">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-start gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.75m0 3.75h.008M10.29 3.86l-8.1 14A2 2 0 003.92 21h16.16a2 2 0 001.73-3.14l-8.1-14a2 2 0 00-3.42 0z"
                            />
                        </svg>

                    </div>

                    <div>

                        <h3 class="text-sm font-semibold text-red-800">
                            Ada PIN fingerprint yang belum terdaftar
                        </h3>

                        <p class="mt-1 text-sm text-red-700">
                            Terdapat
                            <span class="font-semibold">
                                {{ $belumTerdaftar }} PIN
                            </span>
                            yang belum terhubung dengan Data Karyawan.
                        </p>

                        <p class="mt-1 text-xs text-red-600">
                            Data scan tetap tersimpan dan akan otomatis terhubung
                            setelah PIN ditambahkan ke Data Karyawan.
                        </p>

                    </div>

                </div>

                <a
                    href="{{ route('attendance.unregistered') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700"
                >

                    Lihat PIN Belum Terdaftar

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>

                </a>

            </div>

        </div>

    @endif


    {{-- ============================= --}}
    {{-- STATISTIK --}}
    {{-- ============================= --}}

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

        {{-- TOTAL SCAN --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Total Scan
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalScan }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Seluruh data fingerprint yang tersimpan
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
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Scan Hari Ini
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $scanHariIni }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Data fingerprint pada hari ini
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

    </div>


    {{-- ============================= --}}
    {{-- FILTER --}}
    {{-- ============================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5">

            <h3 class="text-base font-semibold text-slate-800">
                Filter Data
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Cari data fingerprint berdasarkan PIN atau tanggal.
            </p>

        </div>

        <form
            method="GET"
            action="{{ route('attendance.index') }}"
        >

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                {{-- PIN --}}
                <div>

                    <label
                        for="search"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        PIN Fingerprint
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Cari PIN..."
                        value="{{ request('search') }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- TANGGAL --}}
                <div>

                    <label
                        for="tanggal"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Tanggal
                    </label>

                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        value="{{ request('tanggal') }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- TOMBOL --}}
                <div class="flex items-end gap-2 md:col-span-2 xl:col-span-2">

                    <button
                        type="submit"
                        class="inline-flex h-[42px] items-center justify-center rounded-lg bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('attendance.index') }}"
                        class="inline-flex h-[42px] items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- ============================= --}}
    {{-- TABEL DATA FINGERPRINT --}}
    {{-- ============================= --}}

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h3 class="text-base font-semibold text-slate-800">
                Riwayat Scan Fingerprint
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Data yang diterima dari mesin fingerprint.
            </p>

        </div>


        @if($logs->count())

            <div class="w-full overflow-x-auto">

                <table class="min-w-[1050px] w-full text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                No
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                PIN
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                Nama Karyawan
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                Tanggal
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                Waktu
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                Status
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                Verifikasi
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                Device
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($logs as $log)

                            @php
                                $isUnregistered = !$log->employee;
                            @endphp

                            <tr class="transition {{ $isUnregistered ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-slate-50' }}">

                                {{-- NO --}}
                                <td class="whitespace-nowrap px-5 py-3 {{ $isUnregistered ? 'text-red-500' : 'text-slate-500' }}">
                                    {{ $logs->firstItem() + $loop->index }}
                                </td>


                                {{-- PIN --}}
                                <td class="whitespace-nowrap px-5 py-3">

                                    <span class="rounded-md px-2.5 py-1 font-semibold
                                        {{ $isUnregistered
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-slate-100 text-slate-700'
                                        }}">
                                        {{ $log->pin }}
                                    </span>

                                </td>


                                {{-- NAMA --}}
                                <td class="px-5 py-3">

                                    @if($log->employee)

                                        <span class="font-medium text-slate-800">
                                            {{ $log->employee->name }}
                                        </span>

                                    @else

                                        <div class="flex flex-col gap-1">

                                            <span class="inline-flex w-fit rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                Belum terdaftar
                                            </span>

                                            <span class="text-xs text-red-600">
                                                Tambahkan PIN ini ke Data Karyawan
                                            </span>

                                        </div>

                                    @endif

                                </td>


                                {{-- TANGGAL --}}
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $log->punched_at?->format('d-m-Y') ?? '-' }}
                                </td>


                                {{-- WAKTU --}}
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $log->punched_at?->format('H:i:s') ?? '-' }}
                                </td>


                                {{-- STATUS --}}
                                <td class="whitespace-nowrap px-5 py-3">

                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                        {{ $log->status }}
                                    </span>

                                </td>


                                {{-- VERIFIKASI --}}
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $log->verify }}
                                </td>


                                {{-- DEVICE --}}
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $log->device_serial ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="px-6 py-12 text-center"
                                >

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-7 w-7"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0119 7.414V19a2 2 0 01-2 2Z"
                                            />
                                        </svg>

                                    </div>

                                    <p class="mt-4 text-sm font-medium text-slate-700">
                                        Belum ada data fingerprint
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Data absensi dari mesin fingerprint belum tersedia.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            <div class="border-t border-slate-200 px-6 py-4">

                {{ $logs->links() }}

            </div>

        @else

            {{-- EMPTY STATE --}}
            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0119 7.414V19a2 2 0 01-2 2Z"
                        />
                    </svg>

                </div>

                <p class="mt-4 text-sm font-medium text-slate-700">
                    Belum ada data fingerprint
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Data absensi dari mesin fingerprint belum tersedia.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection