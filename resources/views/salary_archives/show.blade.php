@extends('layouts.app')

@section('title', 'Detail Arsip Gaji')

@section('content')

    {{-- ============================= --}}
    {{-- HEADER --}}
    {{-- ============================= --}}

    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    ">

        <div>
            <h1 class="page-title">
                Detail Arsip Gaji
            </h1>

            <p class="page-description" style="margin-bottom: 0;">
                Detail data perhitungan gaji karyawan borongan.
            </p>
        </div>

        <a
            href="{{ route('salary_archives.index') }}"
            class="btn btn-secondary"
        >
            ← Kembali
        </a>

    </div>


    {{-- ============================= --}}
    {{-- INFORMASI ARSIP --}}
    {{-- ============================= --}}

    <div class="card">

        <h3 style="
            margin-top: 0;
            margin-bottom: 20px;
        ">
            Informasi Arsip
        </h3>

        <div style="
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        ">

            {{-- NAMA ARSIP --}}
            <div>

                <div style="
                    font-size: 12px;
                    color: #6b7280;
                    margin-bottom: 5px;
                ">
                    Nama Arsip
                </div>

                <strong>
                    {{ $salaryArchive->archive_name }}
                </strong>

            </div>


            {{-- PERIODE --}}
            <div>

                <div style="
                    font-size: 12px;
                    color: #6b7280;
                    margin-bottom: 5px;
                ">
                    Periode
                </div>

                <strong>
                    {{ $salaryArchive->start_date->format('d/m/Y') }}
                    s/d
                    {{ $salaryArchive->end_date->format('d/m/Y') }}
                </strong>

            </div>


            {{-- TOTAL KARYAWAN --}}
            <div>

                <div style="
                    font-size: 12px;
                    color: #6b7280;
                    margin-bottom: 5px;
                ">
                    Total Karyawan
                </div>

                <strong>
                    {{ $salaryArchive->total_employees }}
                    orang
                </strong>

            </div>


            {{-- TOTAL CHECKLOG --}}
            <div>

                <div style="
                    font-size: 12px;
                    color: #6b7280;
                    margin-bottom: 5px;
                ">
                    Total Checklog
                </div>

                <strong>
                    {{ $salaryArchive->total_checklogs }}
                    data
                </strong>

            </div>

        </div>

    </div>


    {{-- ============================= --}}
    {{-- TOTAL GAJI --}}
    {{-- ============================= --}}

    <div class="card">

        <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">

            <div>

                <div style="
                    font-size: 13px;
                    color: #6b7280;
                    margin-bottom: 5px;
                ">
                    Total Gaji Arsip
                </div>

                <div style="
                    font-size: 26px;
                    font-weight: bold;
                ">
                    Rp {{ number_format((float) $salaryArchive->total_salary, 0, ',', '.') }}
                </div>

            </div>

        </div>

    </div>


    {{-- ============================= --}}
    {{-- FILTER DATA ARSIP --}}
    {{-- ============================= --}}

    <div class="card">

        <h3 style="
            margin-top: 0;
            margin-bottom: 20px;
        ">
            Filter Data
        </h3>


        <form
            action="{{ route('salary_archives.show', $salaryArchive) }}"
            method="GET"
        >

            <div style="
                display: grid;
                grid-template-columns: 1fr 1fr 2fr auto auto;
                gap: 15px;
                align-items: end;
            ">

                {{-- DARI TANGGAL --}}
                <div class="form-group" style="margin-bottom: 0;">

                    <label for="filter_tanggal_mulai">
                        Dari Tanggal
                    </label>

                    <input
                        type="date"
                        id="filter_tanggal_mulai"
                        name="filter_tanggal_mulai"
                        value="{{ request('filter_tanggal_mulai') }}"
                    >

                </div>


                {{-- SAMPAI TANGGAL --}}
                <div class="form-group" style="margin-bottom: 0;">

                    <label for="filter_tanggal_selesai">
                        Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        id="filter_tanggal_selesai"
                        name="filter_tanggal_selesai"
                        value="{{ request('filter_tanggal_selesai') }}"
                    >

                </div>


                {{-- NAMA KARYAWAN --}}
                <div class="form-group" style="margin-bottom: 0;">

                    <label for="nama_karyawan">
                        Nama Karyawan
                    </label>

                    <input
                        type="text"
                        id="nama_karyawan"
                        name="nama_karyawan"
                        value="{{ request('nama_karyawan') }}"
                        placeholder="Cari nama karyawan..."
                    >

                </div>


                {{-- CARI --}}
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Cari
                </button>


                {{-- RESET --}}
                <a
                    href="{{ route('salary_archives.show', $salaryArchive) }}"
                    class="btn btn-secondary"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- ============================= --}}
    {{-- PESAN --}}
    {{-- ============================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ============================= --}}
    {{-- DATA CHECKLOG --}}
    {{-- ============================= --}}

    <div class="card">

        <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        ">

            <div>

                <h3 style="margin: 0;">
                    Data Checklog
                </h3>

                <small style="color: #6b7280;">
                    Data kehadiran yang termasuk dalam arsip ini.
                </small>

            </div>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>No</th>
                        <th>No. ID</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Scan Masuk</th>
                        <th>Scan Pulang</th>
                        <th>Istirahat</th>
                        <th>Total Jam Kerja</th>
                        <th>Gaji</th>
                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    {{-- PENTING:
                         Gunakan $checklogs agar hasil filter dari controller tampil --}}
                    @forelse($checklogs as $checklog)

                        <tr>

                            {{-- NO --}}
                            <td>
                                {{ $loop->iteration }}
                            </td>


                            {{-- NO ID --}}
                            <td>
                                {{ $checklog->employee->no_id ?? '-' }}
                            </td>


                            {{-- NAMA --}}
                            <td style="text-align: left;">
                                {{ $checklog->employee->name ?? '-' }}
                            </td>


                            {{-- TANGGAL --}}
                            <td>
                                {{ $checklog->tanggal->format('d/m/Y') }}
                            </td>


                            {{-- JAM MASUK --}}
                            <td>
                                {{ $checklog->jam_masuk ?? '-' }}
                            </td>


                            {{-- JAM PULANG --}}
                            <td>
                                {{ $checklog->jam_pulang ?? '-' }}
                            </td>


                            {{-- SCAN MASUK --}}
                            <td>
                                {{ $checklog->scan_masuk ?? '-' }}
                            </td>


                            {{-- SCAN PULANG --}}
                            <td>
                                {{ $checklog->scan_pulang ?? '-' }}
                            </td>


                            {{-- ISTIRAHAT --}}
                            <td>
                                {{ number_format((float) $checklog->jam_istirahat, 2, '.', '') }}
                                jam
                            </td>


                            {{-- TOTAL JAM KERJA --}}
                            <td>
                                {{ number_format((float) $checklog->total_jam_kerja, 2, ',', '.') }}
                                jam
                            </td>


                            {{-- GAJI --}}
                            <td>
                                <strong>
                                    Rp {{ number_format((float) $checklog->gaji, 0, ',', '.') }}
                                </strong>
                            </td>


                            {{-- AKSI --}}
                            <td>

                                <div class="actions">

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route(
                                            'salary_archives.editChecklog',
                                            [$salaryArchive, $checklog]
                                        ) }}"
                                        class="btn btn-warning"
                                    >
                                        Edit
                                    </a>


                                    {{-- HAPUS --}}
                                    <form
                                        action="{{ route(
                                            'salary_archives.destroyChecklog',
                                            [$salaryArchive, $checklog]
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data checklog ini?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="12"
                                style="
                                    padding: 40px;
                                    color: #6b7280;
                                "
                            >
                                Tidak ada data checklog yang sesuai dengan filter.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ============================= --}}
    {{-- FOOTER TOTAL --}}
    {{-- ============================= --}}

    <div style="
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    ">

        <div style="
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px 20px;
        ">

            <span style="color: #6b7280;">
                Total Gaji:
            </span>

            <strong style="font-size: 18px;">
                Rp {{ number_format((float) $salaryArchive->total_salary, 0, ',', '.') }}
            </strong>

        </div>

    </div>

@endsection