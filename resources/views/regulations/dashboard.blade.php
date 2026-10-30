
@extends('layouts.app')

@section('title', 'Dashboard Regulasi')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <header class="relative overflow-hidden rounded-3xl bg-slate-950 px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="absolute -right-12 -top-20 h-64 w-64 rounded-full bg-emerald-500/20 blur-3xl" aria-hidden="true"></div>
        <div class="absolute bottom-0 right-1/4 h-32 w-32 rounded-full bg-teal-400/10 blur-2xl" aria-hidden="true"></div>

        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
                    General Affairs · PT Starfood International
                </p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    Dashboard Regulasi
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                    Pantau data regulasi, status pemenuhan, dan masa berlaku perijinan perusahaan.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-2 text-xs font-medium text-slate-200">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Data per {{ now()->format('d-m-Y') }}
                </span>
                <a href="{{ route('regulations.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    Lihat Data Regulasi
                    <span aria-hidden="true" class="ml-2">→</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Statistik --}}
    <section aria-label="Ringkasan regulasi" class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total Regulasi --}}
        <a href="{{ route('regulations.index') }}"
           class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total regulasi</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                        {{ number_format($totalRegulasi, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500">Seluruh data regulasi tercatat</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-600" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- Terpenuhi --}}
        <a href="{{ route('regulations.index', ['status' => 'Terpenuhi']) }}"
           class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Terpenuhi</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-emerald-700">
                        {{ number_format($terpenuhi, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500">Regulasi yang sudah terpenuhi</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- Tidak Terpenuhi --}}
        <a href="{{ route('regulations.index', ['status' => 'Tidak Terpenuhi']) }}"
           class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-rose-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Tidak terpenuhi</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-rose-700">
                        {{ number_format($tidakTerpenuhi, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500">Perlu tindak lanjut pemenuhan</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.86L1.82 18.5A2 2 0 003.55 21h16.9a2 2 0 001.73-2.5L13.7 3.86a2 2 0 00-3.4 0z"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- Expired --}}
        <a href="{{ route('regulations.index', ['masa_berlaku' => 'expired']) }}"
           class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Sudah expired</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-amber-600">
                        {{ number_format($expired, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500">Perijinan melewati masa berlaku</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
        </a>
    </section>

    {{-- Status Masa Berlaku --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Status masa berlaku</h2>
            <p class="mt-1 text-sm text-slate-500">Ringkasan regulasi berdasarkan sisa masa berlaku perijinan.</p>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">

            <a href="{{ route('regulations.index', ['masa_berlaku' => '30-hari']) }}"
               class="rounded-xl border border-orange-200 bg-orange-50 p-5 transition hover:-translate-y-0.5 hover:shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-orange-700">Berakhir ≤ 30 hari</p>
                        <p class="mt-2 text-2xl font-bold text-orange-700">
                            {{ number_format($segeraBerakhir, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-orange-600">Segera lakukan tindak lanjut</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/70 text-orange-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
            </a>

            <a href="{{ route('regulations.index', ['masa_berlaku' => '31-90-hari']) }}"
               class="rounded-xl border border-yellow-200 bg-yellow-50 p-5 transition hover:-translate-y-0.5 hover:shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-yellow-700">Berakhir 31–90 hari</p>
                        <p class="mt-2 text-2xl font-bold text-yellow-700">
                            {{ number_format($akanBerakhir, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-yellow-600">Perlu dipersiapkan</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/70 text-yellow-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
            </a>

            <a href="{{ route('regulations.index', ['masa_berlaku' => 'lebih-90-hari']) }}"
               class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 transition hover:-translate-y-0.5 hover:shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-emerald-700">Lebih dari 90 hari</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-700">
                            {{ number_format($masihAktif, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-emerald-600">Masa berlaku masih aman</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/70 text-emerald-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                </div>
            </a>

        </div>
    </section>

    {{-- Regulasi Akan Berakhir --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-end sm:justify-between sm:p-6">
            <div>
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                    <h2 class="text-base font-semibold text-slate-900">Regulasi yang akan berakhir</h2>
                </div>
                <p class="mt-1 text-sm text-slate-500">
                    Daftar regulasi dengan masa berlaku maksimal 90 hari ke depan.
                </p>
            </div>

            <a href="{{ route('regulations.index') }}"
               class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                Lihat semua <span aria-hidden="true">→</span>
            </a>
        </div>

        @if($regulasiAkanBerakhir->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">Jenis perijinan</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Nomor ijin</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Bagian</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Tanggal berakhir</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Sisa</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($regulasiAkanBerakhir as $regulation)
                            @php
                                $hariTersisa = now()->startOfDay()->diffInDays(
                                    $regulation->tanggal_berakhir->startOfDay(),
                                    false
                                );
                            @endphp

                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">
                                        {{ $regulation->jenis_perijinan }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $regulation->kategori }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                    {{ $regulation->nomor_ijin ?: '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $regulation->bagian_terkait ?: '-' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                    {{ $regulation->tanggal_berakhir->format('d-m-Y') }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($hariTersisa <= 30)
                                        <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700">
                                            {{ $hariTersisa }} hari
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-semibold text-yellow-700">
                                            {{ $hariTersisa }} hari
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <a href="{{ route('regulations.show', $regulation) }}"
                                       class="font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
                                        Lihat detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center px-6 py-12 text-center">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <p class="mt-3 text-sm font-semibold text-slate-700">
                    Tidak ada regulasi yang akan berakhir.
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Belum ada regulasi dengan masa berlaku maksimal 90 hari.
                </p>
            </div>
        @endif
    </section>

</div>
@endsection
