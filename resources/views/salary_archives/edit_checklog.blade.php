@extends('layouts.app')

@section('title', 'Edit Checklog')

@section('content')
    <style>
        :root {
            --laravel-bg: #f8fafc;
            --laravel-panel: #ffffff;
            --laravel-card: #ffffff;
            --laravel-border: #e2e8f0;
            --laravel-text: #0f172a;
            --laravel-muted: #64748b;
            --laravel-primary: #4f46e5;
            --laravel-primary-dark: #4338ca;
            --laravel-soft: #eef2ff;
            --laravel-success: #10b981;
            --laravel-danger: #dc2626;
        }

        .checklog-page {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            background: var(--laravel-panel);
            border: 1px solid var(--laravel-border);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        }

        .page-header h1 {
            margin: 0 0 8px;
            font-size: 28px;
            color: var(--laravel-text);
            letter-spacing: -0.03em;
        }

        .page-header p {
            margin: 0;
            color: var(--laravel-muted);
            font-size: 14px;
        }

        .info-card,
        .form-card {
            background: var(--laravel-card);
            border: 1px solid var(--laravel-border);
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
            padding: 24px;
        }

        .section-title {
            margin: 0 0 18px;
            font-size: 20px;
            color: var(--laravel-text);
            letter-spacing: -0.02em;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .info-item {
            background: #f8fafc;
            border: 1px solid var(--laravel-border);
            border-radius: 12px;
            padding: 14px 16px;
        }

        .info-label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--laravel-muted);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .info-value {
            font-size: 15px;
            color: var(--laravel-text);
            font-weight: 700;
        }

        .alert {
            border-radius: 12px;
            border: 1px solid rgba(220, 38, 38, 0.1);
            background: #fef2f2;
            color: var(--laravel-danger);
            box-shadow: none;
        }

        .alert ul {
            margin: 10px 0 0 18px;
        }

        .field-wrap {
            max-width: 420px;
        }

        .field-wrap label {
            display: block;
            font-weight: 700;
            color: var(--laravel-text);
            margin-bottom: 10px;
        }

        .field-wrap input {
            width: 100%;
            border: 1px solid #cbd5e1;
            background: white;
            border-radius: 10px;
            padding: 11px 12px;
            font-size: 14px;
            color: var(--laravel-text);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field-wrap input:focus {
            border-color: var(--laravel-primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
            outline: none;
        }

        .helper-text {
            display: block;
            margin-top: 8px;
            color: var(--laravel-muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .summary-box {
            background: #f8fafc;
            border: 1px solid var(--laravel-border);
            border-radius: 14px;
            padding: 18px;
            margin: 22px 0;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .summary-item {
            background: white;
            border: 1px solid var(--laravel-border);
            border-radius: 12px;
            padding: 14px;
        }

        .summary-item .label {
            display: block;
            font-size: 11px;
            color: var(--laravel-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .summary-item strong {
            color: var(--laravel-text);
            font-size: 15px;
        }

        .actions {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn {
            border-radius: 10px;
            padding: 11px 16px;
            font-weight: 700;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(0.98);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--laravel-primary) 0%, var(--laravel-primary-dark) 100%);
            color: white;
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.16);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-grid,
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="checklog-page">
        <div class="page-header">
            <div>
                <h1>Edit Checklog</h1>
                <p>Edit data waktu istirahat dan perhitungan gaji karyawan.</p>
            </div>

            <a href="{{ route('salary_archives.show', $salaryArchive) }}" class="btn btn-secondary">
                ← Kembali
            </a>
        </div>

        <div class="info-card">
            <h3 class="section-title">Informasi Checklog</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Karyawan</span>
                    <span class="info-value">{{ $checklog->employee->name ?? '-' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">No. ID</span>
                    <span class="info-value">{{ $checklog->employee->no_id ?? '-' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value">{{ $checklog->tanggal->format('d/m/Y') }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Scan Masuk</span>
                    <span class="info-value">{{ $checklog->scan_masuk ?? '-' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Scan Pulang</span>
                    <span class="info-value">{{ $checklog->scan_pulang ?? '-' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Jam Masuk</span>
                    <span class="info-value">{{ $checklog->jam_masuk ?? '-' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Jam Pulang</span>
                    <span class="info-value">{{ $checklog->jam_pulang ?? '-' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Tarif Per Jam</span>
                    <span class="info-value">Rp {{ number_format((float) $checklog->hourly_rate, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Data belum dapat disimpan.</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <h3 class="section-title">Edit Perhitungan</h3>

            <form action="{{ route('salary_archives.updateChecklog', [$salaryArchive, $checklog]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="field-wrap">
                    <label for="jam_istirahat">Jam Istirahat</label>
                    <input
                        type="text"
                        id="jam_istirahat"
                        name="jam_istirahat"
                        value="{{ old('jam_istirahat', number_format((float) $checklog->jam_istirahat, 2, '.', '')) }}"
                        placeholder="Contoh: 0.44 atau 1.20"
                        required
                    >
                    <small class="helper-text">
                        Format jam.menit. Menit dapat diisi bebas dari 00 sampai 59. Contoh: 0.44 = 44 menit,
                        1.20 = 1 jam 20 menit.
                    </small>
                </div>

                <div class="summary-box">
                    <div class="summary-grid">
                        <div class="summary-item">
                            <span class="label">Total Jam Kerja Saat Ini</span>
                            <strong>{{ number_format((float) $checklog->total_jam_kerja, 2, ',', '.') }} jam</strong>
                        </div>

                        <div class="summary-item">
                            <span class="label">Gaji Saat Ini</span>
                            <strong>Rp {{ number_format((float) $checklog->gaji, 0, ',', '.') }}</strong>
                        </div>

                        <div class="summary-item">
                            <span class="label">Tarif Per Jam</span>
                            <strong>Rp {{ number_format((float) $checklog->hourly_rate, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('salary_archives.show', $salaryArchive) }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection