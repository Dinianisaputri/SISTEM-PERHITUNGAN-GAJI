@extends('layouts.app')

@section('title', 'Karyawan Belum Terdaftar')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <p class="text-sm font-medium text-red-600">
                DATA FINGERPRINT
            </p>

            <h1 class="mt-1 text-2xl font-bold text-slate-800">
                Karyawan Belum Terdaftar
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Daftar PIN fingerprint yang belum terhubung dengan Data Karyawan.
            </p>
        </div>


        <div class="flex gap-2">

            <a
                href="{{ route('attendance.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
            >
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
                        d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                    />
                </svg>

                Data Absensi
            </a>


            <a
                href="{{ route('employees.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
            >
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
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Tambah Karyawan
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PERINGATAN --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-red-200 bg-red-50 p-5">

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
                    PIN belum terdaftar di Data Karyawan
                </h3>

                <p class="mt-1 text-sm leading-6 text-red-700">
                    Data fingerprint tetap disimpan meskipun PIN belum memiliki
                    data karyawan. Tambahkan PIN tersebut ke Data Karyawan
                    agar riwayat scan otomatis terhubung.
                </p>

                <p class="mt-2 text-xs text-red-600">
                    Pastikan PIN Fingerprint yang dimasukkan sama dengan PIN pada mesin.
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DAFTAR PIN BELUM TERDAFTAR --}}
    {{-- ========================================================= --}}

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-base font-semibold text-slate-800">
                Daftar PIN Belum Terdaftar
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Setiap PIN ditampilkan satu kali berdasarkan riwayat fingerprint.
            </p>

        </div>


        @if($pinBelumTerdaftar->count())

            <div class="w-full overflow-x-auto">

                <table class="min-w-[900px] w-full text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-slate-600">
                                No
                            </th>

                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-slate-600">
                                PIN Fingerprint
                            </th>

                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-slate-600">
                                Total Scan
                            </th>

                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-slate-600">
                                Scan Terakhir
                            </th>

                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-slate-600">
                                Status
                            </th>

                            <th class="whitespace-nowrap px-6 py-3 font-semibold text-slate-600">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($pinBelumTerdaftar as $index => $item)

                            <tr class="transition hover:bg-red-50/50">

                                {{-- NO --}}
                                <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                    {{ $index + 1 }}
                                </td>


                                {{-- PIN --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="rounded-md bg-red-100 px-3 py-1.5 font-semibold text-red-700">
                                        {{ $item->pin }}
                                    </span>

                                </td>


                                {{-- TOTAL SCAN --}}
                                <td class="whitespace-nowrap px-6 py-4 text-slate-700">

                                    <span class="font-semibold">
                                        {{ $item->total_scan }}
                                    </span>

                                    <span class="text-slate-500">
                                        kali
                                    </span>

                                </td>


                                {{-- SCAN TERAKHIR --}}
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">

                                    @if($item->terakhir_scan)

                                        {{ \Carbon\Carbon::parse($item->terakhir_scan)->format('d-m-Y H:i:s') }}

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        Belum Terdaftar
                                    </span>

                                </td>


                                {{-- AKSI --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <a
                                        href="{{ route('employees.create', ['fingerprint_pin' => $item->pin]) }}"
                                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-blue-700"
                                    >

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
                                                d="M12 4v16m8-8H4"
                                            />
                                        </svg>

                                        Tambah Karyawan

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">

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
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                </div>

                <p class="mt-4 text-sm font-semibold text-slate-700">
                    Semua PIN sudah terdaftar
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Tidak ada PIN fingerprint yang belum terhubung dengan Data Karyawan.
                </p>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- DETAIL RIWAYAT SCAN --}}
    {{-- ========================================================= --}}

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-base font-semibold text-slate-800">
                Detail Riwayat Scan
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Seluruh scan dari PIN yang belum terdaftar.
            </p>

        </div>


        @if($logs->count())

            <div class="w-full overflow-x-auto">

                <table class="min-w-[1000px] w-full text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                No
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-semibold text-slate-600">
                                PIN
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

                        @foreach($logs as $log)

                            <tr class="transition hover:bg-red-50/40">

                                {{-- NO --}}
                                <td class="whitespace-nowrap px-5 py-3 text-slate-500">
                                    {{ $logs->firstItem() + $loop->index }}
                                </td>


                                {{-- PIN --}}
                                <td class="whitespace-nowrap px-5 py-3">

                                    <span class="rounded-md bg-red-100 px-2.5 py-1 font-semibold text-red-700">
                                        {{ $log->pin }}
                                    </span>

                                </td>


                                {{-- TANGGAL --}}
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $log->punched_at?->format('d-m-Y') ?? '-' }}
                                </td>


                                {{-- WAKTU --}}
                                <td class="whitespace-nowrap px-5 py-3 font-medium text-slate-700">
                                    {{ $log->punched_at?->format('H:i:s') ?? '-' }}
                                </td>


                                {{-- STATUS --}}
                                <td class="whitespace-nowrap px-5 py-3">

                                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        Belum Terdaftar
                                    </span>

                                </td>


                                {{-- VERIFIKASI --}}
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $log->verify ?? '-' }}
                                </td>


                                {{-- DEVICE --}}
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $log->device_sn ?? '-' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if($logs->hasPages())

                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $logs->links() }}
                </div>

            @endif

        @else

            <div class="px-6 py-12 text-center">

                <p class="text-sm font-medium text-slate-700">
                    Tidak ada riwayat scan.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection