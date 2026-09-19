@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div>
        <h1 class="page-title">
            Dashboard
        </h1>

        <p class="page-description">
            Ringkasan data perhitungan gaji karyawan borongan.
        </p>
    </div>


    <div class="stats">

        <div class="stat-card">
            <div class="stat-title">
                Total Karyawan Borongan
            </div>

            <div class="stat-value">
                {{ $totalKaryawan }}
            </div>

            <div style="margin-top: 5px; color: #6b7280; font-size: 12px;">
                Orang
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">
                Total Checklog
            </div>

            <div class="stat-value">
                {{ $totalChecklog }}
            </div>

            <div style="margin-top: 5px; color: #6b7280; font-size: 12px;">
                Data kehadiran
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">
                Total Arsip Gaji
            </div>

            <div class="stat-value">
                {{ $totalArsip }}
            </div>

            <div style="margin-top: 5px; color: #6b7280; font-size: 12px;">
                Arsip
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">
                Total Gaji
            </div>

            <div class="stat-value" style="font-size: 21px;">
                Rp {{ number_format((float) $totalGaji, 0, ',', '.') }}
            </div>

            <div style="margin-top: 5px; color: #6b7280; font-size: 12px;">
                Total seluruh arsip
            </div>
        </div>

    </div>


    <div class="card">

        <h3 style="margin-top: 0; margin-bottom: 10px;">
            Sistem Perhitungan Gaji Borongan
        </h3>

        <p style="
            margin: 0;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.7;
        ">
            Sistem digunakan untuk mengelola data karyawan borongan,
            mengimpor data fingerprint, menghitung jam kerja berdasarkan
            waktu scan masuk dan scan pulang, serta mengarsipkan hasil
            perhitungan gaji.
        </p>

    </div>

@endsection