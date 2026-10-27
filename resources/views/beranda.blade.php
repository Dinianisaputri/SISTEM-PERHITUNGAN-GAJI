@extends('layouts.app')

@section('title', 'Dashboard HRD')

@section('content')

@php

    $jumlahJenisKaryawan = max((int) $totalBorongan + (int) $totalHarian + (int) $totalBulanan, 1);

    $persenBorongan = round(((int) $totalBorongan / $jumlahJenisKaryawan) * 100);

    $persenHarian = round(((int) $totalHarian / $jumlahJenisKaryawan) * 100);

    $persenBulanan = max(0, 100 - $persenBorongan - $persenHarian);

@endphp

<style>
    .dashboard-analysis {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 20px;
        align-items: start;
    }
    .dashboard-analysis > div { min-width: 0; }
    .dashboard-analysis a { overflow-wrap: anywhere; }
    .dashboard-hero { padding-top: 28px; padding-bottom: 22px; }
    .dashboard-hero-main { align-items: center !important; gap: 24px !important; }
    .dashboard-hero-summary { margin-top: 22px !important; padding-top: 14px !important; line-height: 1.6; }
    .dashboard-hero-actions { flex-shrink: 0; }
    .dashboard-analysis-card { min-height: 100%; }
    .dashboard-analysis-heading { min-height: 54px; }
    @media (max-width: 1023px) {
        .dashboard-analysis { grid-template-columns: minmax(0, 1fr); }
        .dashboard-hero-main { align-items: flex-start !important; }
    }
    @media (max-width: 640px) {
        .dashboard-hero { padding: 22px 20px 18px !important; }
        .dashboard-hero-actions { width: 100%; }
        .dashboard-hero-actions a { flex: 1 1 auto; }
        .dashboard-hero-summary { align-items: flex-start !important; flex-direction: column; gap: 8px !important; }
    }
</style>

<div class="space-y-7 pb-8">

    {{-- Header utama --}}

    <section class="dashboard-hero relative overflow-hidden rounded-3xl px-6 py-7 shadow-sm sm:px-8 sm:py-9"

        style="background-color: #123f38; color: #ffffff;">

        <div class="pointer-events-none absolute -right-10 -top-20 h-64 w-64 rounded-full border-[36px] border-white/5"></div>

        <div class="pointer-events-none absolute right-24 top-24 h-24 w-24 rounded-full bg-emerald-300/10 blur-2xl"></div>

        <div class="dashboard-hero-main relative z-10 flex flex-col justify-between gap-6 lg:flex-row lg:items-center">

            <div class="max-w-2xl">

                <div class="mb-4 inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold tracking-wide"

                    style="border-color: rgba(255,255,255,.18); background-color: rgba(255,255,255,.10); color: #d1fae5;">

                    <span class="h-2 w-2 rounded-full bg-emerald-300"></span>

                    SISTEM INFORMASI HRD · STARFOOD INTERNATIONAL

                </div>

                <h1 class="text-3xl font-bold tracking-tight sm:text-4xl" style="color: #ffffff;">Dashboard HRD</h1>

                <p class="mt-3 max-w-xl text-sm leading-6 sm:text-base" style="color: #e7fff5;">

                    Pantau data karyawan, aktivitas fingerprint, dan kepatuhan regulasi dalam satu tempat.

                </p>

            </div>

            <div class="dashboard-hero-actions" style="display:flex;flex-wrap:wrap;align-items:center;gap:12px;">

                <a href="{{ route('employees.index') }}" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:48px;padding:12px 18px;border-radius:12px;background:#ffffff;color:#123f38;font-size:14px;font-weight:700;text-decoration:none;white-space:nowrap;">

                    <svg style="width:16px;height:16px;flex-shrink:0;display:block;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M20 8v6m3-3h-6"/></svg>

                    Data Karyawan

                </a>

                <a href="{{ route('attendance.index') }}" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:48px;padding:12px 18px;border:1px solid rgba(255,255,255,.28);border-radius:12px;background:rgba(255,255,255,.08);color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;white-space:nowrap;">

                    Lihat Fingerprint

                    <svg style="width:16px;height:16px;flex-shrink:0;display:block;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 17 17 7M7 7h10v10"/></svg>

                </a>

            </div>

        </div>

        <div class="dashboard-hero-summary" style="position:relative;z-index:10;display:flex;flex-wrap:wrap;align-items:center;gap:12px 24px;margin-top:22px;border-top:1px solid rgba(255,255,255,.18);padding-top:14px;color:#e7fff5;font-size:12px;line-height:1.6;">

            <span style="display:inline-flex;align-items:center;gap:8px;"><span style="display:inline-block;width:6px;height:6px;border-radius:999px;background:#6ee7b7;"></span>Ringkasan sistem</span>

            <span>Data ditampilkan berdasarkan informasi yang tersimpan di aplikasi</span>

        </div>

    </section>

    {{-- KPI utama --}}

    <section>

        <div class="mb-4 flex flex-wrap items-end justify-between gap-2">

            <div>

                <h2 class="text-lg font-bold text-slate-800">Ikhtisar sistem</h2>

                <p class="mt-1 text-sm text-slate-500">Angka utama untuk membantu pemantauan operasional.</p>

            </div>

            <span class="text-xs font-medium text-slate-400">RINGKASAN DATA</span>

        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <a href="{{ route('employees.index') }}" class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">

                <div class="flex items-start justify-between gap-3">

                    <div>

                        <p class="text-sm font-medium text-slate-500">Total karyawan aktif</p>

                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $totalKaryawan }}</p>

                    </div>

                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>

                    </span>

                </div>

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">

                    <span class="text-slate-400">Data karyawan</span><span class="font-semibold text-emerald-700 group-hover:underline">Buka data →</span>

                </div>

            </a>

            <a href="{{ route('attendance.index') }}" class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">

                <div class="flex items-start justify-between gap-3">

                    <div>

                        <p class="text-sm font-medium text-slate-500">Total scan fingerprint</p>

                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $totalFingerprint }}</p>

                    </div>

                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-700">

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 11a2 2 0 0 0-2 2c0 2.5-.5 4.5-1.5 6"/><path d="M14 13c0 3.1-.4 5.3-1.4 7"/><path d="M8 14c0 1.8-.3 3.4-1 4.8"/><path d="M6 11a6 6 0 0 1 12 0c0 1.5-.1 2.8-.3 4"/><path d="M4 9a8 8 0 0 1 16 0"/><path d="M12 3v2"/></svg>

                    </span>

                </div>

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">

                    <span class="text-slate-400">Seluruh data scan</span><span class="font-semibold text-sky-700 group-hover:underline">Lihat data →</span>

                </div>

            </a>

            <a href="{{ route('attendance.index') }}" class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-200 hover:shadow-md">

                <div class="flex items-start justify-between gap-3">

                    <div>

                        <p class="text-sm font-medium text-slate-500">Scan hari ini</p>

                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $scanHariIni }}</p>

                    </div>

                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-700">

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>

                    </span>

                </div>

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">

                    <span class="text-slate-400">Aktivitas hari ini</span><span class="font-semibold text-violet-700 group-hover:underline">Periksa →</span>

                </div>

            </a>

            <a href="{{ route('regulations.index') }}" class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-md">

                <div class="flex items-start justify-between gap-3">

                    <div>

                        <p class="text-sm font-medium text-slate-500">Total regulasi</p>

                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $totalRegulasi }}</p>

                    </div>

                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-700">

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h8"/></svg>

                    </span>

                </div>

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">

                    <span class="text-slate-400">Dokumen terkelola</span><span class="font-semibold text-amber-700 group-hover:underline">Lihat regulasi →</span>

                </div>

            </a>

        </div>

    </section>

    {{-- Area analisis dan perhatian --}}

    <section class="dashboard-analysis">

        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm" style="min-width:0;">

            <div class="dashboard-analysis-heading flex items-start justify-between gap-4">

                <div>

                    <h2 class="text-base font-bold text-slate-800">Komposisi karyawan</h2>

                    <p class="mt-1 text-sm text-slate-500">Perbandingan jenis karyawan yang tercatat.</p>

                </div>

                <a href="{{ route('employees.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Lihat semua →</a>

            </div>

            <div class="mt-6 space-y-5">

                <a href="{{ route('employees.index', ['jenis_karyawan' => 'Borongan']) }}" class="block">

                    <div class="mb-2 flex items-center justify-between gap-3">

                        <span class="flex items-center gap-2 text-sm font-medium text-slate-700"><span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>Borongan</span>

                        <span class="text-sm font-semibold text-slate-800">{{ $totalBorongan }} <span class="font-normal text-slate-400">orang</span></span>

                    </div>

                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-emerald-600 transition-all" style="width: {{ $persenBorongan }}%"></div></div>

                </a>

                <a href="{{ route('employees.index', ['jenis_karyawan' => 'Harian']) }}" class="block">

                    <div class="mb-2 flex items-center justify-between gap-3">

                        <span class="flex items-center gap-2 text-sm font-medium text-slate-700"><span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span>Harian</span>

                        <span class="text-sm font-semibold text-slate-800">{{ $totalHarian }} <span class="font-normal text-slate-400">orang</span></span>

                    </div>

                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-sky-500 transition-all" style="width: {{ $persenHarian }}%"></div></div>

                </a>

                <a href="{{ route('employees.index', ['jenis_karyawan' => 'Bulanan']) }}" class="block">

                    <div class="mb-2 flex items-center justify-between gap-3">

                        <span class="flex items-center gap-2 text-sm font-medium text-slate-700"><span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>Bulanan</span>

                        <span class="text-sm font-semibold text-slate-800">{{ $totalBulanan }} <span class="font-normal text-slate-400">orang</span></span>

                    </div>

                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-violet-500 transition-all" style="width: {{ $persenBulanan }}%"></div></div>

                </a>

            </div>

            <p class="mt-5 border-t border-slate-100 pt-4 text-xs leading-5 text-slate-400">Persentase dihitung dari total jenis karyawan yang ditampilkan pada bagian ini.</p>

        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm" style="min-width:0;">

            <div>

                <h2 class="text-base font-bold text-slate-800">Perlu diperhatikan</h2>

                <p class="mt-1 text-sm text-slate-500">Ringkasan hal yang perlu dicek.</p>

            </div>

            <div class="mt-5 space-y-3">

                <a href="{{ route('attendance.unregistered') }}" class="flex items-center gap-3 rounded-xl border border-red-100 bg-red-50/70 p-4 transition hover:bg-red-50">

                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-red-600">

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg>

                    </span>

                    <span class="min-w-0 flex-1"><span class="block text-sm font-semibold text-slate-800">PIN belum terdaftar</span><span class="mt-0.5 block text-xs text-slate-500">Periksa kecocokan data fingerprint</span></span>

                    <span class="text-lg font-bold text-red-600">{{ $fingerprintBelumTerdaftar }}</span>

                </a>

                <a href="{{ route('regulations.index') }}" class="flex items-center gap-3 rounded-xl border border-amber-100 bg-amber-50/70 p-4 transition hover:bg-amber-50">

                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-amber-600">

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 9v4m0 4h.01"/><path d="M10.3 3.9 1.8 18.5A1.7 1.7 0 0 0 3.3 21h17.4a1.7 1.7 0 0 0 1.5-2.5L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>

                    </span>

                    <span class="min-w-0 flex-1"><span class="block text-sm font-semibold text-slate-800">Regulasi akan kedaluwarsa</span><span class="mt-0.5 block text-xs text-slate-500">Dalam 30 hari ke depan</span></span>

                    <span class="text-lg font-bold text-amber-700">{{ $regulasiAkanExpired }}</span>

                </a>

                <a href="{{ route('regulations.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-slate-100">

                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-slate-600">

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 6h13M8 12h13M8 18h13"/><path d="M3 6h.01M3 12h.01M3 18h.01"/></svg>

                    </span>

                    <span class="min-w-0 flex-1"><span class="block text-sm font-semibold text-slate-800">Regulasi belum terpenuhi</span><span class="mt-0.5 block text-xs text-slate-500">Perlu ditindaklanjuti</span></span>

                    <span class="text-lg font-bold text-slate-700">{{ $regulasiBelumTerpenuhi }}</span>

                </a>

            </div>

        </div>

    </section>

    {{-- Akses cepat --}}

    <section>

        <div class="mb-4">

            <h2 class="text-lg font-bold text-slate-800">Akses cepat</h2>

            <p class="mt-1 text-sm text-slate-500">Langsung menuju menu yang sering digunakan.</p>

        </div>

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">

            <a href="{{ route('salary.index') }}" class="group flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 transition hover:border-emerald-200 hover:shadow-sm">

                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">

                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18M7 15h3"/></svg>

                </span>

                <span class="min-w-0 flex-1"><span class="block text-sm font-semibold text-slate-800">Penggajian</span><span class="mt-1 block text-xs text-slate-500">Kelola data gaji</span></span>

                <span class="text-slate-300 transition group-hover:translate-x-1 group-hover:text-emerald-700">→</span>

            </a>

            <a href="{{ route('salary_archives.index') }}" class="group flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 transition hover:border-emerald-200 hover:shadow-sm">

                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-700">

                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/></svg>

                </span>

                <span class="min-w-0 flex-1"><span class="block text-sm font-semibold text-slate-800">Arsip gaji</span><span class="mt-1 block text-xs text-slate-500">Lihat riwayat penggajian</span></span>

                <span class="text-slate-300 transition group-hover:translate-x-1 group-hover:text-sky-700">→</span>

            </a>

            <a href="{{ route('work-schedules.index') }}" class="group flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 transition hover:border-emerald-200 hover:shadow-sm">

                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-700">

                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>

                </span>

                <span class="min-w-0 flex-1"><span class="block text-sm font-semibold text-slate-800">Master jam kerja</span><span class="mt-1 block text-xs text-slate-500">Atur jadwal kerja</span></span>

                <span class="text-slate-300 transition group-hover:translate-x-1 group-hover:text-violet-700">→</span>

            </a>

            <a href="{{ route('regulations.index') }}" class="group flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 transition hover:border-emerald-200 hover:shadow-sm">

                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">

                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>

                </span>

                <span class="min-w-0 flex-1"><span class="block text-sm font-semibold text-slate-800">Regulasi</span><span class="mt-1 block text-xs text-slate-500">Dokumen dan kepatuhan</span></span>

                <span class="text-slate-300 transition group-hover:translate-x-1 group-hover:text-amber-700">→</span>

            </a>

        </div>

    </section>

    <footer class="flex flex-col justify-between gap-1 border-t border-slate-200 pt-4 text-xs text-slate-400 sm:flex-row">

        <span>Sistem Informasi HRD · PT Starfood International</span>

        <span>Dashboard ringkasan operasional</span>

    </footer>

</div>

@endsection
