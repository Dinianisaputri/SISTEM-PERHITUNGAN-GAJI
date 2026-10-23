@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">
                DATA KARYAWAN
            </p>

            <h1 class="mt-1 text-2xl font-bold text-slate-800">
                Dashboard Karyawan
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Ringkasan data karyawan PT Starfood International.
            </p>
        </div>

        <div class="flex gap-2">
            <a
                href="{{ route('employees.index') }}"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                Kelola Data
            </a>

            <a
                href="{{ route('employees.create') }}"
                class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700"
            >
                + Tambah Karyawan
            </a>
        </div>
    </div>


    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- STATISTIK UTAMA --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- TOTAL KARYAWAN --}}
        <a
            href="{{ route('employees.index') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md"
        >
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">
                        Total Karyawan
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalKaryawan }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        Seluruh data karyawan
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15 19a4 4 0 00-8 0m4-8a3 3 0 100-6 3 3 0 000 6zm8 8a4 4 0 00-3-3.87M17 5a3 3 0 010 6"/>
                    </svg>
                </div>
            </div>
        </a>


        {{-- KARYAWAN AKTIF --}}
        <a
            href="{{ route('employees.index', ['status' => 'aktif']) }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-md"
        >
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">
                        Karyawan Aktif
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-emerald-600">
                        {{ $karyawanAktif }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        Status karyawan aktif
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-100">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </a>


        {{-- KARYAWAN NONAKTIF --}}
        <a
            href="{{ route('employees.index', ['status' => 'nonaktif']) }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-slate-300 hover:shadow-md"
        >
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">
                        Karyawan Nonaktif
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-slate-600">
                        {{ $karyawanNonaktif }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        Status karyawan nonaktif
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition group-hover:bg-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </a>


        {{-- KONTRAK AKAN BERAKHIR --}}
        <a
            href="{{ route('employees.index', ['kontrak' => 'akan-berakhir']) }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-amber-200 hover:shadow-md"
        >
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">
                        Kontrak Akan Berakhir
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-amber-500">
                        {{ $kontrakAkanBerakhir }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        Dalam 30 hari
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-500 transition group-hover:bg-amber-100">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </a>

    </div>


    {{-- JENIS KARYAWAN --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- BORONGAN --}}
        <a
            href="{{ route('employees.index', ['jenis_karyawan' => 'Borongan']) }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">
                        Karyawan Borongan
                    </p>

                    <p class="mt-2 text-2xl font-bold text-blue-600">
                        {{ $karyawanBorongan }}
                    </p>
                </div>

                <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition group-hover:bg-blue-100">
                    Borongan
                </span>
            </div>
        </a>


        {{-- BULANAN --}}
        <a
            href="{{ route('employees.index', ['jenis_karyawan' => 'Bulanan']) }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-violet-200 hover:shadow-md"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">
                        Karyawan Bulanan
                    </p>

                    <p class="mt-2 text-2xl font-bold text-violet-600">
                        {{ $karyawanBulanan }}
                    </p>
                </div>

                <span class="rounded-lg bg-violet-50 px-3 py-1.5 text-xs font-medium text-violet-600 transition group-hover:bg-violet-100">
                    Bulanan
                </span>
            </div>
        </a>


        {{-- HARIAN --}}
        <a
            href="{{ route('employees.index', ['jenis_karyawan' => 'Harian']) }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-cyan-200 hover:shadow-md"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">
                        Karyawan Harian
                    </p>

                    <p class="mt-2 text-2xl font-bold text-cyan-600">
                        {{ $karyawanHarian }}
                    </p>
                </div>

                <span class="rounded-lg bg-cyan-50 px-3 py-1.5 text-xs font-medium text-cyan-600 transition group-hover:bg-cyan-100">
                    Harian
                </span>
            </div>
        </a>

    </div>


    {{-- TOTAL SP --}}
    <a
        href="{{ route('employees.index', ['sp' => 'ada']) }}"
        class="group block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md"
    >
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-blue-600">
                    Total SP
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-700">
                    {{ $totalSP }}
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Surat Peringatan
                </p>
            </div>

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9 12h6m-6 4h4m4-10H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V8a2 2 0 00-2-2zm-3-2H9a2 2 0 00-2 2v2h10V6a2 2 0 00-2-2z"/>
                </svg>
            </div>
        </div>
    </a>

</div>

@endsection