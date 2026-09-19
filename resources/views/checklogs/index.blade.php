@extends('layouts.app')

@section('title', 'Hitung Gaji')

@section('content')

<div style="
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
">

    <div>
        <h1 class="page-title">
            Hitung Gaji
        </h1>

        <p class="page-description" style="margin-bottom: 0;">
            Hitung gaji karyawan borongan berdasarkan data fingerprint.
        </p>
    </div>

</div>


{{-- ============================= --}}
{{-- IMPORT DATA FINGERPRINT --}}
{{-- ============================= --}}

<div class="card">

    <h3 style="
        margin-top: 0;
        margin-bottom: 20px;
    ">
        Import Data Fingerprint
    </h3>

    <form
        action="{{ route('checklogs.import') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <div style="
            display: grid;
            grid-template-columns: 1fr 1fr 2fr auto;
            gap: 15px;
            align-items: end;
        ">

            <div class="form-group" style="margin-bottom: 0;">

                <label for="tanggal_mulai">
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    id="tanggal_mulai"
                    name="tanggal_mulai"
                    value="{{ old('tanggal_mulai') }}"
                    required
                >

            </div>


            <div class="form-group" style="margin-bottom: 0;">

                <label for="tanggal_selesai">
                    Tanggal Selesai
                </label>

                <input
                    type="date"
                    id="tanggal_selesai"
                    name="tanggal_selesai"
                    value="{{ old('tanggal_selesai') }}"
                    required
                >

            </div>


            <div class="form-group" style="margin-bottom: 0;">

                <label for="file">
                    File Fingerprint
                </label>

                <input
                    type="file"
                    id="file"
                    name="file"
                    accept=".xlsx,.xls"
                    required
                >

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Import Excel
            </button>

        </div>

    </form>

</div>


{{-- ============================= --}}
{{-- FILTER DATA --}}
{{-- ============================= --}}

@if($checklogs->count())

<div class="card">

    <h3 style="
        margin-top: 0;
        margin-bottom: 20px;
    ">
        Filter Data
    </h3>

    <form
        action="{{ route('checklogs.index') }}"
        method="GET"
    >

        <div style="
            display: grid;
            grid-template-columns: 1fr 1fr 2fr auto auto;
            gap: 15px;
            align-items: end;
        ">


            {{-- Dari Tanggal --}}

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


            {{-- Sampai Tanggal --}}

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


            {{-- Nama Karyawan --}}

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


            {{-- Cari --}}

            <button
                type="submit"
                class="btn btn-primary"
            >
                Cari
            </button>


            {{-- Reset --}}

            <a
                href="{{ route('checklogs.index') }}"
                class="btn btn-secondary"
            >
                Reset
            </a>

        </div>

    </form>

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

            <h3 style="
                margin: 0 0 5px;
            ">
                Data Checklog
            </h3>

            @if($tanggalMulai && $tanggalSelesai)

                <div style="
                    color: #6b7280;
                    font-size: 13px;
                ">

                    Periode:

                    {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }}

                    s/d

                    {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}

                </div>

            @endif

        </div>


        @if($checklogs->count())

            <div style="
                font-size: 13px;
                color: #6b7280;
            ">

                Menampilkan

                <strong>
                    {{ $checklogs->count() }}
                </strong>

                data

            </div>

        @endif

    </div>


    @if($checklogs->count())


    {{-- ============================= --}}
    {{-- FORM HITUNG SEMUA --}}
    {{-- ============================= --}}

    <form
        action="{{ route('checklogs.hitungSemua') }}"
        method="POST"
    >

        @csrf

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>No</th>
                        <th>No. ID</th>
                        <th>Nama Karyawan</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Scan Masuk</th>
                        <th>Scan Pulang</th>
                        <th>Istirahat</th>
                        <th>Total Jam Kerja</th>
                        <th>Gaji</th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($checklogs as $checklog)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>
                            {{ $checklog->employee->no_id }}
                        </td>


                        <td style="text-align: left;">
                            {{ $checklog->employee->name }}
                        </td>


                        <td>
                            {{ $checklog->tanggal->format('d-m-Y') }}
                        </td>


                        <td>
                            {{ $checklog->jam_masuk ?? '-' }}
                        </td>


                        <td>
                            {{ $checklog->jam_pulang ?? '-' }}
                        </td>


                        <td>
                            {{ $checklog->scan_masuk ?? '-' }}
                        </td>


                        <td>
                            {{ $checklog->scan_pulang ?? '-' }}
                        </td>


                        <td>

                            <input
                                type="text"
                                name="istirahat[{{ $checklog->id }}]"
                                value="{{ $checklog->jam_istirahat ?? '0.00' }}"
                                placeholder="0.00"
                                style="
                                    width: 80px;
                                    text-align: center;
                                "
                            >

                        </td>


                        <td>

                            {{ number_format(
                                (float) $checklog->total_jam_kerja,
                                2,
                                ',',
                                '.'
                            ) }}

                            jam

                        </td>


                        <td>

                            Rp

                            {{ number_format(
                                (float) $checklog->gaji,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- Tombol Hitung Semua --}}

        <div style="
            margin-top: 20px;
        ">

            <button
                type="submit"
                class="btn btn-success"
            >
                Hitung Semua
            </button>

        </div>

    </form>


    {{-- ============================= --}}
    {{-- SIMPAN ARSIP --}}
    {{-- ============================= --}}

    @if($tanggalMulai && $tanggalSelesai)

    <form
        action="{{ route('checklogs.simpanArsip') }}"
        method="POST"
        style="
            margin-top: 10px;
        "
    >

        @csrf

        {{-- Periode asli data yang belum diarsipkan --}}

        <input
            type="hidden"
            name="tanggal_mulai"
            value="{{ \Carbon\Carbon::parse($tanggalMulai)->format('Y-m-d') }}"
        >

        <input
            type="hidden"
            name="tanggal_selesai"
            value="{{ \Carbon\Carbon::parse($tanggalSelesai)->format('Y-m-d') }}"
        >


        <button
            type="submit"
            class="btn btn-primary"
            onclick="return confirm('Simpan semua data gaji pada periode ini ke arsip?')"
        >
            Simpan Arsip Gaji
        </button>

    </form>

    @endif


    @else

    <div style="
        text-align: center;
        padding: 50px 20px;
        color: #6b7280;
    ">

        <div style="
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        ">
            Data tidak ditemukan
        </div>

        <div>
            Silakan ubah filter atau import file fingerprint terlebih dahulu.
        </div>

    </div>

    @endif

</div>

@endsection