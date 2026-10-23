@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <div class="soft-card p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <span class="mini-tag">Dashboard Utama</span>
                    <h1 class="mt-3 text-3xl font-bold text-slate-800">Dashboard</h1>
                    <p class="mt-1 text-sm text-slate-500">Ringkasan data perhitungan gaji karyawan borongan.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="soft-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Total Karyawan</p>
                <div class="mt-4 text-3xl font-bold text-blue-700">{{ $totalKaryawan }}</div>
                <p class="mt-2 text-sm text-slate-500">Orang</p>
            </div>

            <div class="soft-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Total Checklog</p>
                <div class="mt-4 text-3xl font-bold text-blue-700">{{ $totalChecklog }}</div>
                <p class="mt-2 text-sm text-slate-500">Data kehadiran</p>
            </div>

            <div class="soft-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Total Arsip Gaji</p>
                <div class="mt-4 text-3xl font-bold text-blue-700">{{ $totalArsip }}</div>
                <p class="mt-2 text-sm text-slate-500">Arsip</p>
            </div>

            <div class="soft-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Total Gaji</p>
                <div class="mt-4 text-2xl font-bold text-blue-700">Rp {{ number_format((float) $totalGaji, 0, ',', '.') }}</div>
                <p class="mt-2 text-sm text-slate-500">Total seluruh arsip</p>
            </div>
        </div>

        <div class="soft-card p-6">
            <h3 class="text-xl font-bold text-slate-800">Sistem Perhitungan Gaji Borongan</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Sistem digunakan untuk mengelola data karyawan borongan, mengimpor data fingerprint, menghitung jam kerja berdasarkan waktu scan masuk dan scan pulang, serta mengarsipkan hasil perhitungan gaji.
            </p>
        </div>
    </div>
@endsection