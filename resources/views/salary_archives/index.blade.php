@extends('layouts.app')

@section('title', 'Arsip Gaji')

@section('content')

<style>
    .page-header {
        margin-bottom: 22px;
    }

    .page-title {
        margin: 0;
        font-size: 30px;
        color: #111827;
    }

    .page-description {
        margin-top: 8px;
        color: #6b7280;
        font-size: 14px;
    }

    .filter-panel {
        background: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
        border: 1px solid #dbeafe;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 20px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr auto auto;
        gap: 15px;
        align-items: end;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #374151;
    }

    .form-group input {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        color: #111827;
        font-size: 14px;
    }

    .form-group input:focus {
        outline: none;
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .filter-btn,
    .action-link,
    .delete-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-btn,
    .action-link {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
    }

    .filter-btn:hover,
    .action-link:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%);
        color: white;
    }

    .reset-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #f3f4f6;
        color: #374151;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .reset-link:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .table-wrapper {
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: white;
    }

    .archive-table {
        width: 100%;
        border-collapse: collapse;
    }

    .archive-table th {
        background: #f8fafc;
        color: #374151;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .archive-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: center;
        color: #374151;
        font-size: 13px;
        white-space: nowrap;
    }

    .archive-table tbody tr:hover {
        background: #f8fbff;
    }

    .archive-name {
        text-align: left !important;
    }

    .archive-name strong {
        color: #111827;
    }

    .amount {
        font-weight: 700;
        color: #111827;
    }

    .actions {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: center;
    }

    .delete-button {
        background: #dc2626;
        color: white;
    }

    .delete-button:hover {
        background: #b91c1c;
        color: white;
    }

    .empty-state {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 180px;
        color: #6b7280;
        font-size: 15px;
        text-align: center;
        background: #f9fafb;
        border: 1px dashed #d1d5db;
        border-radius: 12px;
    }

    @media (max-width: 900px) {
        .filter-form {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 600px) {
        .filter-form {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Arsip Gaji</h1>
    <p class="page-description">Daftar hasil perhitungan gaji yang telah disimpan.</p>
</div>

<div class="card">

    @if($archives->count())

        <div class="filter-panel">
            <form action="{{ route('salary_archives.index') }}" method="GET" class="filter-form">

                <div class="form-group">
                    <label for="tanggal_mulai">Dari Tanggal</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                </div>

                <div class="form-group">
                    <label for="tanggal_selesai">Sampai Tanggal</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
                </div>

                <div class="form-group">
                    <label for="nama_karyawan">Nama Karyawan</label>
                    <input type="text" id="nama_karyawan" name="nama_karyawan" value="{{ request('nama_karyawan') }}" placeholder="Cari nama karyawan...">
                </div>

                <button type="submit" class="filter-btn">Cari</button>
                <a href="{{ route('salary_archives.index') }}" class="reset-link">Reset</a>

            </form>
        </div>

        <div class="table-wrapper">
            <table class="archive-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Arsip</th>
                        <th>Periode</th>
                        <th>Total Gaji</th>
                        <th>Total Checklog</th>
                        <th>Total Karyawan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($archives as $archive)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td class="archive-name">
                                <strong>{{ $archive->archive_name }}</strong>
                            </td>

                            <td>
                                {{ $archive->start_date->format('d/m/Y') }} s/d {{ $archive->end_date->format('d/m/Y') }}
                            </td>

                            <td>
                                <span class="amount">Rp {{ number_format($archive->total_salary, 0, ',', '.') }}</span>
                            </td>

                            <td>{{ $archive->total_checklogs }}</td>
                            <td>{{ $archive->total_employees }}</td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('salary_archives.show', $archive) }}" class="action-link">Detail</a>

                                    <form action="{{ route('salary_archives.destroy', $archive) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus arsip ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @else
        <div class="empty-state">
            Belum ada arsip gaji.
        </div>
    @endif

</div>

@endsection