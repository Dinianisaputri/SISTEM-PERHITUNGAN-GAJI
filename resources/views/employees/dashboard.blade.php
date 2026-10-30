@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
@php
    $persentaseAktif = $totalKaryawan > 0
        ? round(($karyawanAktif / $totalKaryawan) * 100)
        : 0;
    $totalKaryawanDenganJenis = (int) $karyawanPerJenis->sum('total');
@endphp

<div class="mx-auto max-w-7xl space-y-6">
    <header class="relative overflow-hidden rounded-3xl bg-slate-950 px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="absolute -right-12 -top-20 h-64 w-64 rounded-full bg-emerald-500/20 blur-3xl" aria-hidden="true"></div>
        <div class="absolute bottom-0 right-1/4 h-32 w-32 rounded-full bg-teal-400/10 blur-2xl" aria-hidden="true"></div>

        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
                    Human Resources · PT Starfood International
                </p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    Dashboard Karyawan
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                    Pantau komposisi tenaga kerja, karyawan terbaru, dan tindak lanjut kontrak dari data yang tercatat.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-2 text-xs font-medium text-slate-200">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Data per {{ now()->format('d-m-Y') }}
                </span>
                <a href="{{ route('employees.create') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <span aria-hidden="true" class="mr-2 text-lg leading-none">+</span>
                    Tambah Karyawan
                </a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" role="status">
            {{ session('success') }}
        </div>
    @endif

    <section aria-label="Ringkasan karyawan" class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('employees.index') }}"
           class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total karyawan</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($totalKaryawan, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-slate-500">Seluruh data yang tercatat</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-600" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </span>
            </div>
        </a>

        <a href="{{ route('employees.index', ['status' => 'aktif']) }}"
           class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Karyawan aktif</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-emerald-700">{{ number_format($karyawanAktif, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-slate-500">{{ $persentaseAktif }}% dari total karyawan</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-emerald-100">
                <div class="h-full rounded-full bg-emerald-600" style="width: {{ $persentaseAktif }}%"></div>
            </div>
        </a>

        <a href="{{ route('contracts.index') }}"
           class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Kontrak segera berakhir</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-amber-600">{{ number_format($kontrakAkanBerakhir, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-slate-500">Dalam 30 hari ke depan</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
        </a>

        <a href="{{ route('warning-letters.index') }}"
           class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-rose-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total surat peringatan</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-rose-700">{{ number_format($totalSP, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-slate-500">Surat SP yang tersimpan di sistem</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h4m4-10H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V8a2 2 0 00-2-2zm-3-2H9a2 2 0 00-2 2v2h10V6a2 2 0 00-2-2z"/>
                    </svg>
                </span>
            </div>
        </a>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Komposisi tenaga kerja</h2>
                    <p class="mt-1 text-sm text-slate-500">Sebaran berdasarkan jenis karyawan dan bagian.</p>
                </div>
                <a href="{{ route('employees.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat semua <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jenis karyawan</h3>
                    @if($karyawanPerJenis->isNotEmpty())
                        <div class="mt-4 space-y-4">
                            @foreach($karyawanPerJenis as $jenis)
                                @php
                                    $persentaseJenis = $totalKaryawanDenganJenis > 0
                                        ? round(($jenis->total / $totalKaryawanDenganJenis) * 100)
                                        : 0;
                                @endphp
                                <div>
                                    <div class="mb-1.5 flex items-center justify-between gap-3 text-sm">
                                        <span class="truncate font-medium text-slate-700">{{ $jenis->jenis_karyawan }}</span>
                                        <span class="shrink-0 text-slate-500">{{ number_format($jenis->total, 0, ',', '.') }} <span class="text-xs">({{ $persentaseJenis }}%)</span></span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-emerald-600" style="width: {{ $persentaseJenis }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 rounded-xl bg-slate-50 px-4 py-5 text-sm text-slate-500">Belum ada data jenis karyawan.</p>
                    @endif
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400">Bagian dengan karyawan terbanyak</h3>
                    @if($karyawanPerBagian->isNotEmpty())
                        <div class="mt-4 space-y-4">
                            @foreach($karyawanPerBagian as $bagian)
                                @php
                                    $persentaseBagian = $totalKaryawanDenganBagian > 0
                                        ? round(($bagian->total / $totalKaryawanDenganBagian) * 100)
                                        : 0;
                                @endphp
                                <div>
                                    <div class="mb-1.5 flex items-center justify-between gap-3 text-sm">
                                        <span class="truncate font-medium text-slate-700">{{ $bagian->bagian }}</span>
                                        <span class="shrink-0 text-slate-500">{{ number_format($bagian->total, 0, ',', '.') }} <span class="text-xs">({{ $persentaseBagian }}%)</span></span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-teal-600" style="width: {{ $persentaseBagian }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 rounded-xl bg-slate-50 px-4 py-5 text-sm text-slate-500">Belum ada data bagian.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Karyawan terbaru</h2>
                    <p class="mt-1 text-sm text-slate-500">Diurutkan berdasarkan tanggal mulai kerja.</p>
                </div>
                <a href="{{ route('employees.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Kelola data <span aria-hidden="true">→</span>
                </a>
            </div>

            @if($karyawanTerbaru->isNotEmpty())
                <div class="mt-4 divide-y divide-slate-100">
                    @foreach($karyawanTerbaru as $employee)
                        <a href="{{ route('employees.show', $employee) }}" class="flex items-center gap-3 py-3.5 first:pt-1 last:pb-1 hover:bg-slate-50">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-sm font-bold text-emerald-800">
                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($employee->name, 0, 1)) }}
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-semibold text-slate-800">{{ $employee->name }}</span>
                                <span class="mt-0.5 block truncate text-xs text-slate-500">
                                    {{ $employee->jabatan ?: ($employee->bagian ?: 'Bagian belum diisi') }}
                                    @if($employee->no_induk_karyawan)
                                        · {{ $employee->no_induk_karyawan }}
                                    @endif
                                </span>
                            </span>
                            <span class="shrink-0 text-right">
                                <span class="block text-xs font-medium text-slate-600">{{ $employee->mulai_kerja->format('d-m-Y') }}</span>
                                <span class="mt-0.5 block text-[11px] text-slate-400">{{ $employee->jenis_karyawan ?: 'Jenis belum diisi' }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="mt-5 rounded-xl border border-dashed border-slate-200 px-4 py-8 text-center">
                    <p class="text-sm font-medium text-slate-700">Belum ada tanggal mulai kerja.</p>
                    <p class="mt-1 text-xs text-slate-500">Data karyawan dengan tanggal mulai kerja akan muncul di sini.</p>
                </div>
            @endif
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                    <h2 class="text-base font-semibold text-slate-900">Tindak lanjut kontrak</h2>
                </div>
                <p class="mt-1 text-sm text-slate-500">Karyawan aktif dengan kontrak berakhir dalam 30 hari ke depan.</p>
            </div>
            <a href="{{ route('contracts.index') }}"
               class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                Lihat daftar kontrak <span aria-hidden="true">→</span>
            </a>
        </div>

        @if($kontrakSegeraBerakhir->isNotEmpty())
            <div class="mt-5 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th scope="col" class="pl-0">Karyawan</th>
                            <th scope="col">Bagian</th>
                            <th scope="col">Tanggal berakhir</th>
                            <th scope="col" class="pr-0">Tindak lanjut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($kontrakSegeraBerakhir as $employee)
                            <tr>
                                <td class="pl-0">
                                    <a href="{{ route('employees.show', $employee) }}" class="font-semibold text-slate-800 hover:text-emerald-700">
                                        {{ $employee->name }}
                                    </a>
                                    @if($employee->no_induk_karyawan)
                                        <span class="mt-0.5 block text-xs text-slate-400">{{ $employee->no_induk_karyawan }}</span>
                                    @endif
                                </td>
                                <td class="text-slate-600">{{ $employee->bagian ?: 'Belum diisi' }}</td>
                                <td class="whitespace-nowrap font-medium text-amber-700">{{ $employee->selesai_kontrak->format('d-m-Y') }}</td>
                                <td class="pr-0">
                                    <a href="{{ route('employees.show', $employee) }}" class="font-medium text-emerald-700 hover:underline">Tinjau data</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="mt-5 flex items-start gap-3 rounded-xl bg-emerald-50 px-4 py-4">
                <span class="mt-0.5 text-emerald-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-semibold text-emerald-900">Tidak ada kontrak yang perlu ditindaklanjuti.</p>
                    <p class="mt-1 text-xs text-emerald-800">Belum ada kontrak aktif yang berakhir dalam 30 hari ke depan.</p>
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
