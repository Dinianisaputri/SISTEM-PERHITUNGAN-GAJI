@extends('layouts.app')

@section('content')

<style>
    .holiday-page {
        padding: 32px 40px;
        color: #172033;
    }

    .holiday-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .holiday-title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #172033;
    }

    .holiday-subtitle {
        margin: 6px 0 0;
        font-size: 14px;
        color: #6b7280;
    }

    .holiday-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        background: #087f5b;
        color: white !important;
        text-decoration: none !important;
        font-size: 14px;
        font-weight: 600;
        border: none;
        transition: 0.2s;
    }

    .holiday-add-btn:hover {
        background: #066b4d;
        color: white !important;
    }

    .holiday-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .holiday-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .holiday-card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #172033;
    }

    .holiday-card-count {
        margin-top: 4px;
        font-size: 13px;
        color: #6b7280;
    }

    .holiday-table-wrapper {
        overflow-x: auto;
    }

    .holiday-table {
        width: 100%;
        border-collapse: collapse;
    }

    .holiday-table thead th {
        padding: 14px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #4b5563;
        font-size: 13px;
        font-weight: 600;
        text-align: left;
        white-space: nowrap;
    }

    .holiday-table tbody td {
        padding: 16px 18px;
        border-bottom: 1px solid #f0f1f3;
        font-size: 14px;
        color: #374151;
        vertical-align: middle;
    }

    .holiday-table tbody tr:last-child td {
        border-bottom: none;
    }

    .holiday-table tbody tr:hover {
        background: #fafafa;
    }

    .holiday-number {
        width: 60px;
        color: #6b7280 !important;
    }

    .holiday-date {
        font-weight: 600;
        color: #172033;
    }

    .holiday-day {
        display: block;
        margin-top: 3px;
        font-size: 12px;
        color: #9ca3af;
    }

    .holiday-name {
        font-weight: 600;
        color: #172033;
    }

    .holiday-empty {
        color: #9ca3af;
    }

    .holiday-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .holiday-badge-national {
        background: #e8f3ff;
        color: #1769aa;
    }

    .holiday-badge-company {
        background: #fff4df;
        color: #a16207;
    }

    .holiday-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .holiday-edit-btn,
    .holiday-delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 64px;
        height: 34px;
        padding: 0 12px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none !important;
        cursor: pointer;
        background: white;
        transition: 0.2s;
    }

    .holiday-edit-btn {
        border: 1px solid #dbeafe;
        color: #2563eb !important;
    }

    .holiday-edit-btn:hover {
        background: #eff6ff;
    }

    .holiday-delete-btn {
        border: 1px solid #fecaca;
        color: #dc2626;
    }

    .holiday-delete-btn:hover {
        background: #fef2f2;
    }

    .holiday-alert {
        margin-bottom: 20px;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
    }

    .holiday-alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
    }

    .holiday-empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .holiday-empty-icon {
        font-size: 42px;
        margin-bottom: 12px;
    }

    .holiday-empty-title {
        margin: 0 0 6px;
        font-size: 16px;
        font-weight: 600;
        color: #374151;
    }

    .holiday-empty-text {
        margin: 0 0 20px;
        font-size: 14px;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .holiday-page {
            padding: 24px 16px;
        }

        .holiday-header {
            align-items: flex-start;
            gap: 16px;
            flex-direction: column;
        }

        .holiday-add-btn {
            width: 100%;
            justify-content: center;
        }

        .holiday-table {
            min-width: 850px;
        }
    }
</style>


<div class="holiday-page">

    {{-- HEADER --}}
    <div class="holiday-header">

        <div>
            <h1 class="holiday-title">
                Data Hari Libur
            </h1>

            <p class="holiday-subtitle">
                Kelola kalender hari libur yang digunakan dalam perhitungan lembur.
            </p>
        </div>

        <a href="{{ route('holidays.create') }}"
           class="holiday-add-btn">
            <span style="font-size: 18px; line-height: 1;">+</span>
            Tambah Hari Libur
        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="holiday-alert holiday-alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- CARD --}}
    <div class="holiday-card">

        <div class="holiday-card-header">

            <div>
                <h2 class="holiday-card-title">
                    Daftar Hari Libur
                </h2>

                <div class="holiday-card-count">
                    Total {{ $holidays->count() }} hari libur
                </div>
            </div>

        </div>


        @if($holidays->count() > 0)

            <div class="holiday-table-wrapper">

                <table class="holiday-table">

                    <thead>
                        <tr>
                            <th style="width: 70px;">No</th>
                            <th style="width: 170px;">Tanggal</th>
                            <th>Nama Libur</th>
                            <th>Keterangan</th>
                            <th style="width: 130px;">Jenis</th>
                            <th style="width: 160px; text-align: center;">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($holidays as $holiday)

                            <tr>

                                {{-- NO --}}
                                <td class="holiday-number">
                                    {{ $loop->iteration }}
                                </td>


                                {{-- TANGGAL --}}
                                <td>

                                    <span class="holiday-date">
                                        {{ $holiday->tanggal->format('d/m/Y') }}
                                    </span>

                                    <span class="holiday-day">
                                        {{ $holiday->tanggal->locale('id')->translatedFormat('l') }}
                                    </span>

                                </td>


                                {{-- NAMA --}}
                                <td>
                                    <span class="holiday-name">
                                        {{ $holiday->nama_libur }}
                                    </span>
                                </td>


                                {{-- KETERANGAN --}}
                                <td>

                                    @if($holiday->keterangan)

                                        {{ $holiday->keterangan }}

                                    @else

                                        <span class="holiday-empty">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- JENIS --}}
                                <td>

                                    @if($holiday->is_national)

                                        <span class="holiday-badge holiday-badge-national">
                                            Nasional
                                        </span>

                                    @else

                                        <span class="holiday-badge holiday-badge-company">
                                            Perusahaan
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td>

                                    <div class="holiday-actions">

                                        <a href="{{ route('holidays.edit', $holiday) }}"
                                           class="holiday-edit-btn">
                                            Edit
                                        </a>

                                        <form action="{{ route('holidays.destroy', $holiday) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus hari libur ini?')"
                                              style="margin: 0;">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="holiday-delete-btn">
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="holiday-empty-state">

                <div class="holiday-empty-icon">
                    📅
                </div>

                <h3 class="holiday-empty-title">
                    Belum ada data hari libur
                </h3>

                <p class="holiday-empty-text">
                    Tambahkan tanggal hari libur untuk digunakan
                    dalam perhitungan lembur.
                </p>

                <a href="{{ route('holidays.create') }}"
                   class="holiday-add-btn">
                    + Tambah Hari Libur
                </a>

            </div>

        @endif

    </div>

</div>

@endsection