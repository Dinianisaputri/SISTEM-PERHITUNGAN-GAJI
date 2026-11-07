@extends('layouts.app')

@section('content')

@php
    $jenisKaryawan = strtolower(trim((string) ($employee->jenis_karyawan ?? '')));

    $isBorongan = $jenisKaryawan === 'borongan';
    $isHarian = $jenisKaryawan === 'harian';
    $isBulanan = $jenisKaryawan === 'bulanan';

    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

    $hariHadir = (int) ($salarySummary['hari_hadir'] ?? 0);

    $jamKerja = (float) ($salarySummary['jam_kerja'] ?? 0);

    $jamLemburBiasa = (float) (
        $salarySummary['jam_lembur_biasa'] ?? 0
    );

    $jamLemburLibur = (float) (
        $salarySummary['jam_lembur_libur'] ?? 0
    );

    $jamLembur = $jamLemburBiasa + $jamLemburLibur;

    /*
    |--------------------------------------------------------------------------
    | TARIF LEMBUR
    |--------------------------------------------------------------------------
    */

    $tarifLemburBiasa = 18500;
    $tarifLemburLibur = 37000;

    /*
    |--------------------------------------------------------------------------
    | NOMINAL LEMBUR
    |--------------------------------------------------------------------------
    |
    | Nominal dihitung dari jam lembur yang sudah diberikan controller.
    |
    */

    $totalLemburBiasa = $jamLemburBiasa * $tarifLemburBiasa;

    $totalLemburLibur = $jamLemburLibur * $tarifLemburLibur;

    $totalLembur = $totalLemburBiasa + $totalLemburLibur;

    /*
    |--------------------------------------------------------------------------
    | GAJI
    |--------------------------------------------------------------------------
    */

    $gajiPokok = (float) ($salarySummary['gaji_pokok'] ?? 0);

    $uangMakan = (float) (
        $salarySummary['uang_makan_lembur'] ?? 0
    );

    $totalPotongan = (float) (
        $salarySummary['total_potongan'] ?? 0
    );

    $totalTambahan = (float) (
        $salarySummary['total_tambahan'] ?? 0
    );

    /*
    | Untuk menghindari perbedaan tampilan apabila controller
    | belum mengirim nominal total lembur terbaru, gaji kotor
    | dihitung kembali berdasarkan komponen yang tampil.
    */

    $gajiKotor = $gajiPokok
        + $totalLembur
        + $uangMakan;

    $gajiBersih = max(
        $gajiKotor - $totalPotongan + $totalTambahan,
        0
    );

    /*
    |--------------------------------------------------------------------------
    | TARIF SISTEM
    |--------------------------------------------------------------------------
    */

    

$tarifHarian = (float) ($tarifHarian ?? 0);

$tarifJam = (float) ($tarifJam ?? 0);

$jamIstirahat = $isBorongan
    ? (float) ($salaryDetail->jam_istirahat ?? 1)
    : 0;


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    $queryFilters = request()->only([
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_karyawan',
        'bagian',
        'search',
    ]);
@endphp

<div class="min-h-[calc(100vh-64px)] bg-slate-50">

    <div class="mx-auto max-w-[1450px] px-5 py-5">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="mb-5 rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm backdrop-blur-sm">

            <div class="flex items-center justify-between gap-4">

                <div class="flex min-w-0 items-center gap-4">

                    <a
                        href="{{ route('salary.index', $queryFilters) }}"
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </a>

                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-2">

                            <h1 class="text-lg font-semibold text-slate-900">
                                Detail Gaji
                            </h1>

                            @if($isBorongan)

                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-medium uppercase tracking-wide text-blue-700">
                                    Borongan
                                </span>

                            @elseif($isHarian)

                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-medium uppercase tracking-wide text-emerald-700">
                                    Harian
                                </span>

                            @elseif($isBulanan)

                                <span class="rounded-full bg-violet-50 px-2.5 py-1 text-[10px] font-medium uppercase tracking-wide text-violet-700">
                                    Bulanan
                                </span>

                            @endif

                        </div>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $employee->name }}

                            <span class="mx-1 text-slate-300">·</span>

                            PIN {{ $employee->fingerprint_pin ?: '-' }}

                            <span class="mx-1 text-slate-300">·</span>

                            {{ $employee->bagian ?: '-' }}
                        </p>

                    </div>

                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-right">

                    <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-slate-400">
                        Periode
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-700">
                        {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }}
                        —
                        {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ALERT --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="mb-4 rounded-lg border border-green-100 bg-green-50 px-4 py-2.5 text-sm text-green-700">
                {{ session('success') }}
            </div>

        @endif

        @if($errors->any())

            <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-2.5 text-sm text-red-700">
                {{ $errors->first() }}
            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- MAIN GRID --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1.55fr_1fr]">


            {{-- ===================================================== --}}
            {{-- LEFT --}}
            {{-- ===================================================== --}}

            <div class="space-y-5">


                {{-- ================================================= --}}
                {{-- STATISTICS --}}
                {{-- ================================================= --}}

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 bg-gradient-to-r from-slate-50 to-white md:grid-cols-4 md:divide-y-0">

                        {{-- HARI HADIR --}}
                        <div class="px-5 py-4">

                            <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                Hari Hadir
                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $hariHadir }}

                                <span class="text-xs font-medium text-slate-400">
                                    hari
                                </span>
                            </p>

                        </div>


                        {{-- JAM KERJA --}}
                        <div class="px-5 py-4">

                            <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                Jam Kerja
                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ number_format($jamKerja, 0, ',', '.') }}

                                <span class="text-xs font-medium text-slate-400">
                                    jam
                                </span>
                            </p>

                        </div>


                        {{-- LEMBUR BIASA --}}
                        @if(!$isBorongan)

                            <div class="px-5 py-4">

                                <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                    Lembur Biasa
                                </p>

                                <p class="mt-2 text-2xl font-bold text-slate-900">
                                    {{ number_format($jamLemburBiasa, 0, ',', '.') }}

                                    <span class="text-xs font-medium text-slate-400">
                                        jam
                                    </span>
                                </p>

                            </div>


                            {{-- LEMBUR LIBUR --}}
                            <div class="px-5 py-4">

                                <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                    Lembur Libur
                                </p>

                                <p class="mt-2 text-2xl font-bold text-orange-600">
                                    {{ number_format($jamLemburLibur, 0, ',', '.') }}

                                    <span class="text-xs font-medium text-slate-400">
                                        jam
                                    </span>
                                </p>

                            </div>

                        @else

                            {{-- BORONGAN --}}
                            <div class="px-5 py-4">

                                <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                    Tarif / Jam
                                </p>

                                <p class="mt-2 text-xl font-bold text-slate-900">
                                    Rp {{ number_format($tarifJam, 0, ',', '.') }}
                                </p>

                            </div>


                            <div class="px-5 py-4">

                                <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                    Uang Makan
                                </p>

                                <p class="mt-2 text-xl font-bold text-slate-900">
                                    Rp {{ number_format($uangMakan, 0, ',', '.') }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ABSENSI --}}
                {{-- ================================================= --}}

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">

                        <div>

                            <h2 class="text-sm font-semibold text-slate-800">
                                Data Absensi
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                Absensi yang digunakan dalam perhitungan.
                            </p>

                        </div>

                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-500">
                            {{ count($attendance) }} hari
                        </span>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-xs">

                            <thead class="bg-gray-50 text-gray-400">

                                <tr>

                                    <th class="px-4 py-2.5 text-left font-medium">
                                        Tanggal
                                    </th>

                                    <th class="px-4 py-2.5 text-left font-medium">
                                        Jadwal
                                    </th>

                                    <th class="px-4 py-2.5 text-left font-medium">
                                        Masuk Aktual
                                    </th>

                                    <th class="px-4 py-2.5 text-left font-medium">
                                        Pulang Aktual
                                    </th>

                                    <th class="px-4 py-2.5 text-right font-medium">
                                        Kerja
                                    </th>

                                    <th class="px-4 py-2.5 text-right font-medium">
                                        Pendapatan Harian
                                    </th>

                                    @if(!$isBorongan)

                                        <th class="px-4 py-2.5 text-right font-medium">
                                            Lembur Biasa
                                        </th>

                                        <th class="px-4 py-2.5 text-right font-medium">
                                            Lembur Libur
                                        </th>

                                    @else
                                        <th class="px-4 py-2.5 text-right font-medium">
                                            Istirahat
                                        </th>

                                        <th class="px-4 py-2.5 text-right font-medium">
                                            Uang Makan
                                        </th>

                                    @endif

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @forelse($attendance as $row)

                                    @php
                                        $tanggal = $row['tanggal'] ?? null;

                                        $isHoliday = $row['is_holiday'] ?? false;

                                        $rowJamKerja = (float) (
                                            $row['jam_kerja'] ?? 0
                                        );

                                        $rowLemburBiasa = (float) (
                                            $row['jam_lembur_biasa'] ?? 0
                                        );

                                        $rowLemburLibur = (float) (
                                            $row['jam_lembur_libur'] ?? 0
                                        );

                                        $rowUangMakan = (float) (
                                            $row['uang_makan_lembur'] ?? 0
                                        );

                                        $rowPendapatanHarian = (float) (
                                            $row['pendapatan_harian'] ?? 0
                                        );
                                    @endphp

                                    <tr class="hover:bg-gray-50">

                                        {{-- TANGGAL --}}
                                        <td class="whitespace-nowrap px-4 py-2.5 text-gray-700">

                                            {{ $tanggal
                                                ? \Carbon\Carbon::parse($tanggal)->format('d M')
                                                : '-'
                                            }}

                                            @if($isHoliday)

                                                <span class="ml-1 text-orange-500">
                                                    Libur
                                                </span>

                                            @endif

                                        </td>


                                        {{-- JADWAL KERJA --}}
                                        <td class="whitespace-nowrap px-4 py-2.5 text-gray-600">
                                            @if(!empty($row['jam_masuk_jadwal']) && !empty($row['jam_pulang_jadwal']))
                                                {{ substr((string) $row['jam_masuk_jadwal'], 0, 5) }} - {{ substr((string) $row['jam_pulang_jadwal'], 0, 5) }}
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        {{-- MASUK AKTUAL DARI FINGERPRINT --}}
                                        <td class="px-4 py-2.5 text-gray-600">
                                            {{ $row['jam_masuk'] ?? '-' }}
                                        </td>


                                        {{-- PULANG AKTUAL DARI FINGERPRINT --}}
                                        <td class="px-4 py-2.5 text-gray-600">
                                            {{ $row['jam_pulang'] ?? '-' }}
                                        </td>


                                        {{-- KERJA --}}
                                        <td class="px-4 py-2.5 text-right text-gray-700">

                                            {{ number_format($rowJamKerja, 0, ',', '.') }}

                                        </td>

                                        <td class="whitespace-nowrap px-4 py-2.5 text-right font-medium text-slate-800">
                                            Rp {{ number_format($rowPendapatanHarian, 0, ',', '.') }}
                                        </td>

                                        @if($isBorongan)
                                            <td class="px-2 py-2 text-right">
                                                <form
                                                    action="{{ route('salary.attendance.break.update', $employee->id) }}"
                                                    method="POST"
                                                    class="flex items-center justify-end gap-1"
                                                >
                                                    @csrf
                                                    @method('PUT')
                                                    @foreach($queryFilters as $key => $value)
                                                        @if($value !== null && $value !== '')
                                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                        @endif
                                                    @endforeach
                                                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                                    <input
                                                        type="number"
                                                        name="jam_istirahat"
                                                        min="0"
                                                        max="24"
                                                        step="0.01"
                                                        value="{{ number_format((float) ($row['jam_istirahat'] ?? 1), 2, '.', '') }}"
                                                        aria-label="Jam istirahat {{ $tanggal }}"
                                                        class="w-16 rounded border-gray-200 px-1.5 py-1 text-right text-xs"
                                                    >
                                                    <button
                                                        type="submit"
                                                        class="text-indigo-600 hover:underline"
                                                    >
                                                        Simpan
                                                    </button>
                                                </form>
                                            </td>
                                        @endif

                                        @if(!$isBorongan)

                                            {{-- LEMBUR BIASA --}}
                                            <td class="px-4 py-2.5 text-right">

                                                @if($rowLemburBiasa > 0)

                                                    <span class="font-medium text-orange-600">

                                                        {{ number_format(
                                                            $rowLemburBiasa,
                                                            0,
                                                            ',',
                                                            '.'
                                                        ) }}

                                                    </span>

                                                @else

                                                    <span class="text-gray-300">
                                                        -
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- LEMBUR LIBUR --}}
                                            <td class="px-4 py-2.5 text-right">

                                                @if($rowLemburLibur > 0)

                                                    <span class="font-medium text-red-600">

                                                        {{ number_format(
                                                            $rowLemburLibur,
                                                            0,
                                                            ',',
                                                            '.'
                                                        ) }}

                                                    </span>

                                                @else

                                                    <span class="text-gray-300">
                                                        -
                                                    </span>

                                                @endif

                                            </td>

                                        @else

                                            {{-- UANG MAKAN BORONGAN --}}
                                            <td class="px-4 py-2.5 text-right">

                                                @if($rowUangMakan > 0)

                                                    <span class="font-medium text-emerald-600">

                                                        Rp {{ number_format(
                                                            $rowUangMakan,
                                                            0,
                                                            ',',
                                                            '.'
                                                        ) }}

                                                    </span>

                                                @else

                                                    <span class="text-gray-300">
                                                        -
                                                    </span>

                                                @endif

                                            </td>

                                        @endif

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="8"
                                            class="px-4 py-8 text-center text-gray-400"
                                        >
                                            Belum ada data absensi.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <p class="mt-2 text-xs text-gray-400">
                        Pendapatan harian mencakup gaji pokok, lembur, dan uang makan; potongan atau tambahan dihitung pada total periode.
                    </p>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RIGHT --}}
            {{-- ===================================================== --}}

            <div class="space-y-5">


                {{-- ================================================= --}}
                {{-- PERHITUNGAN GAJI --}}
                {{-- ================================================= --}}

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-100 px-5 py-4">

                        <h2 class="text-sm font-semibold text-slate-800">
                            Perhitungan Gaji
                        </h2>

                    </div>


                    <div class="space-y-1 px-5 py-4">


                        {{-- ================================================= --}}
                        {{-- GAJI POKOK --}}
                        {{-- ================================================= --}}

                        <div class="flex items-center justify-between py-2">

                            <span class="text-sm text-gray-600">
                                Gaji Pokok
                            </span>

                            <span class="text-sm font-medium text-gray-800">
                                Rp {{ number_format(
                                    $gajiPokok,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </span>

                        </div>


                        @if(!$isBorongan)

                            {{-- ================================================= --}}
                            {{-- LEMBUR BIASA --}}
                            {{-- ================================================= --}}

                            <div class="flex items-center justify-between py-2">

                                <div>

                                    <span class="text-sm text-gray-600">
                                        Lembur Biasa
                                    </span>

                                    <p class="text-[10px] text-gray-400">
                                        {{ number_format(
                                            $jamLemburBiasa,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                        jam × Rp18.500
                                    </p>

                                </div>

                                <span class="text-sm font-medium text-gray-800">
                                    Rp {{ number_format(
                                        $totalLemburBiasa,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </span>

                            </div>


                            {{-- ================================================= --}}
                            {{-- LEMBUR LIBUR --}}
                            {{-- ================================================= --}}

                            <div class="flex items-center justify-between py-2">

                                <div>

                                    <span class="text-sm text-gray-600">
                                        Lembur Libur
                                    </span>

                                    <p class="text-[10px] text-gray-400">
                                        {{ number_format(
                                            $jamLemburLibur,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                        jam × Rp37.000
                                    </p>

                                </div>

                                <span class="text-sm font-medium text-gray-800">
                                    Rp {{ number_format(
                                        $totalLemburLibur,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </span>

                            </div>


                            {{-- ================================================= --}}
                            {{-- UANG MAKAN LEMBUR --}}
                            {{-- ================================================= --}}

                            <div class="flex items-center justify-between py-2">

                                <span class="text-sm text-gray-600">
                                    Uang Makan
                                </span>

                                <span class="text-sm font-medium text-gray-800">
                                    Rp {{ number_format(
                                        $uangMakan,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </span>

                            </div>

                        @else

                            {{-- ================================================= --}}
                            {{-- UANG MAKAN BORONGAN --}}
                            {{-- ================================================= --}}

                            <div class="flex items-center justify-between py-2">

                                <div>

                                    <span class="text-sm text-gray-600">
                                        Uang Makan
                                    </span>

                                    <p class="text-[10px] text-gray-400">
                                        1× per hari jika jam kerja ≥ 10 jam
                                    </p>

                                </div>

                                <span class="text-sm font-medium text-gray-800">
                                    Rp {{ number_format(
                                        $uangMakan,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </span>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- GAJI KOTOR --}}
                        {{-- ================================================= --}}

                        <div class="mt-3 border-t border-slate-100 pt-4">

                            <div class="flex items-center justify-between">

                                <span class="text-sm font-semibold text-slate-700">
                                    Gaji Kotor
                                </span>

                                <span class="text-sm font-bold text-slate-900">
                                    Rp {{ number_format(
                                        $gajiKotor,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </span>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- POTONGAN --}}
                        {{-- ================================================= --}}

                        <div class="mt-5 rounded-xl bg-slate-50 p-3">

                            <div class="mb-2 flex items-center justify-between">

                                <span class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                                    Potongan
                                </span>

                                <button
                                    type="button"
                                    onclick="openAddDeduction()"
                                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-slate-600 transition hover:border-slate-300 hover:text-slate-900"
                                >
                                    + Tambah
                                </button>

                            </div>


                            @if($deductions->count())

                                <div class="divide-y divide-slate-200">

                                    @foreach($deductions as $deduction)

                                        <div class="flex items-start justify-between gap-3 py-2.5">

                                            <div class="min-w-0">

                                                <p class="text-sm font-medium text-slate-700">
                                                    {{ $deduction->nama_potongan }}
                                                </p>

                                                @if($deduction->keterangan)

                                                    <p class="mt-0.5 text-[11px] text-slate-400">
                                                        {{ $deduction->keterangan }}
                                                    </p>

                                                @endif

                                            </div>


                                            <div class="flex items-center gap-2">

                                                <span class="whitespace-nowrap text-sm font-semibold text-red-600">

                                                    -Rp {{ number_format(
                                                        $deduction->nominal,
                                                        0,
                                                        ',',
                                                        '.'
                                                    ) }}

                                                </span>


                                                {{-- EDIT --}}
                                                <button
                                                    type="button"
                                                    onclick='openEditDeduction(
                                                        @json($deduction->id),
                                                        @json($deduction->nama_potongan),
                                                        @json($deduction->nominal),
                                                        @json($deduction->keterangan)
                                                    )'
                                                    class="rounded-md p-1 text-slate-400 transition hover:bg-slate-200 hover:text-slate-700"
                                                    title="Edit"
                                                >

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3.5 w-3.5"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m1.5-7.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 7.5-7.5z"
                                                        />
                                                    </svg>

                                                </button>


                                                {{-- DELETE --}}
                                                <form
                                                    action="{{ route('salary.deduction.destroy', [$employee->id, $deduction->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus potongan ini?')"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    @foreach($queryFilters as $key => $value)

                                                        @if($value !== null && $value !== '')

                                                            <input
                                                                type="hidden"
                                                                name="{{ $key }}"
                                                                value="{{ $value }}"
                                                            >

                                                        @endif

                                                    @endforeach

                                                    <button
                                                        type="submit"
                                                        class="rounded-md p-1 text-slate-400 transition hover:bg-red-50 hover:text-red-600"
                                                        title="Hapus"
                                                    >
                                                        ×
                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <p class="py-2 text-xs text-slate-400">
                                    Belum ada potongan.
                                </p>

                            @endif


                            {{-- TOTAL POTONGAN --}}
                            <div class="mt-3 border-t border-slate-200 pt-3">

                                <div class="flex items-center justify-between">

                                    <span class="text-sm font-medium text-slate-600">
                                        Total Potongan
                                    </span>

                                    <span class="text-sm font-semibold text-red-600">
                                        -Rp {{ number_format(
                                            $totalPotongan,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- NOMINAL TAMBAHAN --}}
                        <div class="mt-4 rounded-xl bg-emerald-50 p-3">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-[10px] font-semibold uppercase tracking-[0.14em] text-emerald-700">
                                    Nominal Tambahan
                                </span>
                                <button
                                    type="button"
                                    onclick="openAddAddition()"
                                    class="rounded-lg border border-emerald-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-emerald-700 transition hover:border-emerald-300"
                                >
                                    + Tambah
                                </button>
                            </div>

                            @forelse($additions as $addition)
                                <div class="flex items-start justify-between gap-3 border-b border-emerald-100 py-2.5 last:border-0">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-700">
                                            {{ $addition->nama_tambahan }}
                                        </p>
                                        @if($addition->keterangan)
                                            <p class="mt-0.5 text-[11px] text-slate-400">
                                                {{ $addition->keterangan }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="whitespace-nowrap text-sm font-semibold text-emerald-700">
                                            +Rp {{ number_format($addition->nominal, 0, ',', '.') }}
                                        </span>
                                        <button
                                            type="button"
                                            onclick='openEditAddition(
                                                @json($addition->id),
                                                @json($addition->nama_tambahan),
                                                @json($addition->nominal),
                                                @json($addition->keterangan)
                                            )'
                                            class="rounded-md p-1 text-slate-400 transition hover:bg-emerald-100 hover:text-slate-700"
                                            title="Edit"
                                        >
                                            Edit
                                        </button>
                                        <form
                                            action="{{ route('salary.addition.destroy', [$employee->id, $addition->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus nominal tambahan ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            @foreach($queryFilters as $key => $value)
                                                @if($value !== null && $value !== '')
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endif
                                            @endforeach
                                            <button
                                                type="submit"
                                                class="rounded-md p-1 text-slate-400 transition hover:bg-red-50 hover:text-red-600"
                                                title="Hapus"
                                            >
                                                ×
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="py-2 text-xs text-slate-400">
                                    Belum ada nominal tambahan.
                                </p>
                            @endforelse

                            <div class="mt-3 border-t border-emerald-200 pt-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-600">
                                        Total Nominal Tambahan
                                    </span>
                                    <span class="text-sm font-semibold text-emerald-700">
                                        +Rp {{ number_format($totalTambahan, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>


                        {{-- ================================================= --}}
                        {{-- GAJI BERSIH --}}
                        {{-- ================================================= --}}

                        <div class="mt-5 rounded-xl bg-gradient-to-r from-slate-900 to-slate-700 p-4 text-white">

                            <div class="flex items-end justify-between gap-3">

                                <div>

                                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-300">
                                        Gaji Bersih
                                    </p>

                                    <p class="mt-2 text-[1.7rem] font-bold leading-none">
                                        Rp {{ number_format(
                                            $gajiBersih,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PENGATURAN --}}
                {{-- ================================================= --}}

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-100 px-5 py-4">

                        <h2 class="text-sm font-semibold text-slate-800">
                            Pengaturan
                        </h2>

                    </div>


                    <form
                        action="{{ route('salary.detail.update', $employee->id) }}"
                        method="POST"
                    >

                        @csrf

                        @foreach($queryFilters as $key => $value)

                            @if($value !== null && $value !== '')

                                <input
                                    type="hidden"
                                    name="{{ $key }}"
                                    value="{{ $value }}"
                                >

                            @endif

                        @endforeach


                        <div class="px-5 py-4">

                            <div class="grid grid-cols-2 gap-4">


                                {{-- ================================================= --}}
                                {{-- HARIAN --}}
                                {{-- ================================================= --}}

                                @if($isHarian)

                                    <div>

                                        <label class="mb-1 block text-xs text-gray-400">
                                            Tarif Harian
                                        </label>

                                        <div class="relative">

                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                                Rp
                                            </span>

                                            <input
                                                type="text"
                                               value="{{ number_format($tarifHarian, 0, ',', '.') }}"
                                                readonly
                                                class="w-full cursor-not-allowed rounded-lg border-gray-200 bg-gray-50 py-2 pl-9 text-sm text-gray-600"
                                            >

                                            <input
                                                type="hidden"
                                                name="tarif_harian"
                                               value="{{ number_format($tarifHarian, 0, ',', '.') }}"
                                            >

                                        </div>

                                        <p class="mt-1 text-[11px] text-gray-400">
                                            Tarif otomatis.
                                        </p>

                                    </div>


                                {{-- ================================================= --}}
                                {{-- BULANAN --}}
                                {{-- ================================================= --}}

                                @elseif($isBulanan)

                                    <div>

                                        <label class="mb-1 block text-xs text-gray-400">
                                            Tarif per Hari Hadir
                                        </label>

                                        <div class="relative">

                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                                Rp
                                            </span>

                                            <input
                                                type="text"
                                                value="{{ number_format($tarifHarian, 0, ',', '.') }}"
                                                readonly
                                                class="w-full cursor-not-allowed rounded-lg border-gray-200 bg-gray-50 py-2 pl-9 text-sm text-gray-600"
                                            >

                                        </div>

                                        <p class="mt-1 text-[11px] text-gray-400">
                                            Tarif diambil dari master tarif karyawan dan dikalikan hari hadir.
                                        </p>
                                        @if ($tarifHarian <= 0)
                                            <a href="{{ route('salary-settings.index') }}"
                                               class="mt-1 inline-block text-xs font-medium text-rose-700 underline">
                                                Atur tarif karyawan ini di Master Tarif Gaji
                                            </a>
                                        @endif

                                    </div>


                                {{-- ================================================= --}}
                                {{-- BORONGAN --}}
                                {{-- ================================================= --}}

                                @elseif($isBorongan)

                                    <div>

                                        <label class="mb-1 block text-xs text-gray-400">
                                            Tarif Per Jam
                                        </label>

                                        <div class="relative">

                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                                Rp
                                            </span>

                                            <input
                                                type="text"
                                                value="{{ number_format(18000, 0, ',', '.') }}"
                                                readonly
                                                class="w-full cursor-not-allowed rounded-lg border-gray-200 bg-gray-50 py-2 pl-9 text-sm text-gray-600"
                                            >

                                            <input
                                                type="hidden"
                                                name="tarif_jam"
                                                value="18000"
                                            >

                                        </div>

                                        <p class="mt-1 text-[11px] text-gray-400">
                                            Tarif otomatis.
                                        </p>

                                    </div>

                                @endif


                                {{-- ================================================= --}}
                                {{-- ISTIRAHAT BORONGAN --}}
                                {{-- ================================================= --}}

                                @if($isBorongan)

                                    <div>

                                        <label class="mb-1 block text-xs text-gray-400">
                                            Istirahat
                                        </label>

                                        <div class="relative">

                                            <input
                                                type="number"
                                                name="jam_istirahat"
                                                value="{{ old(
                                                    'jam_istirahat',
                                                    $jamIstirahat
                                                ) }}"
                                                min="0"
                                                step="0.5"
                                                class="w-full rounded-lg border-gray-200 py-2 pr-12 text-sm focus:border-gray-400 focus:ring-0"
                                            >

                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                                jam
                                            </span>

                                        </div>

                                    </div>

                                @endif

                            </div>


                            {{-- ================================================= --}}
                            {{-- KETERANGAN --}}
                            {{-- ================================================= --}}

                            <div class="mt-4">

                                <label class="mb-1 block text-xs text-gray-400">
                                    Keterangan
                                </label>

                                <textarea
                                    name="keterangan"
                                    rows="2"
                                    class="w-full rounded-lg border-gray-200 py-2 text-sm focus:border-gray-400 focus:ring-0"
                                    placeholder="-"
                                >{{ old(
                                    'keterangan',
                                    $salaryDetail->keterangan ?? ''
                                ) }}</textarea>

                            </div>


                            {{-- ================================================= --}}
                            {{-- SIMPAN --}}
                            {{-- ================================================= --}}

                            <div class="mt-4 flex justify-end">

                                <button
                                    type="submit"
                                    class="rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-slate-800"
                                >
                                    Simpan
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================== --}}
{{-- MODAL TAMBAH POTONGAN --}}
{{-- =============================================================== --}}

<div
    id="addDeductionModal"
    class="fixed inset-0 z-50 hidden"
>

    <div
        class="absolute inset-0 bg-black/30"
        onclick="closeAddDeduction()"
    ></div>


    <div class="relative flex min-h-screen items-center justify-center p-4">

        <div class="w-full max-w-md rounded-xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">

                <h2 class="text-sm font-semibold text-gray-800">
                    Tambah Potongan
                </h2>

                <button
                    type="button"
                    onclick="closeAddDeduction()"
                    class="text-gray-400 hover:text-gray-700"
                >
                    ×
                </button>

            </div>


            <form
                action="{{ route('salary.deduction.store', $employee->id) }}"
                method="POST"
            >

                @csrf

                @foreach($queryFilters as $key => $value)

                    @if($value !== null && $value !== '')

                        <input
                            type="hidden"
                            name="{{ $key }}"
                            value="{{ $value }}"
                        >

                    @endif

                @endforeach


                <div class="space-y-4 p-5">


                    {{-- NAMA --}}
                    <div>

                        <label class="mb-1 block text-xs text-gray-500">
                            Nama Potongan
                        </label>

                        <input
                            type="text"
                            name="nama_potongan"
                            required
                            placeholder="Contoh: Kasbon"
                            class="w-full rounded-lg border-gray-200 py-2 text-sm focus:border-gray-400 focus:ring-0"
                        >

                    </div>


                    {{-- NOMINAL --}}
                    <div>

                        <label class="mb-1 block text-xs text-gray-500">
                            Nominal
                        </label>

                        <div class="relative">

                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                Rp
                            </span>

                            <input
                                type="text"
                                name="nominal"
                                required
                                class="rupiah-input w-full rounded-lg border-gray-200 py-2 pl-9 text-sm focus:border-gray-400 focus:ring-0"
                                placeholder="0"
                                inputmode="numeric"
                                autocomplete="off"
                            >

                        </div>

                    </div>


                    {{-- KETERANGAN --}}
                    <div>

                        <label class="mb-1 block text-xs text-gray-500">
                            Keterangan
                        </label>

                        <textarea
                            name="keterangan"
                            rows="2"
                            placeholder="Opsional"
                            class="w-full rounded-lg border-gray-200 py-2 text-sm focus:border-gray-400 focus:ring-0"
                        ></textarea>

                    </div>

                </div>


                <div class="flex justify-end gap-2 border-t border-gray-100 px-5 py-4">

                    <button
                        type="button"
                        onclick="closeAddDeduction()"
                        class="rounded-lg border border-gray-200 px-4 py-2 text-xs font-medium text-gray-600"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-xs font-medium text-white"
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================== --}}
{{-- MODAL EDIT POTONGAN --}}
{{-- =============================================================== --}}

<div
    id="editDeductionModal"
    class="fixed inset-0 z-50 hidden"
>

    <div
        class="absolute inset-0 bg-black/30"
        onclick="closeEditDeduction()"
    ></div>


    <div class="relative flex min-h-screen items-center justify-center p-4">

        <div class="w-full max-w-md rounded-xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">

                <h2 class="text-sm font-semibold text-gray-800">
                    Edit Potongan
                </h2>

                <button
                    type="button"
                    onclick="closeEditDeduction()"
                    class="text-gray-400 hover:text-gray-700"
                >
                    ×
                </button>

            </div>


            <form
                id="editDeductionForm"
                method="POST"
            >

                @csrf
                @method('PUT')

                @foreach($queryFilters as $key => $value)

                    @if($value !== null && $value !== '')

                        <input
                            type="hidden"
                            name="{{ $key }}"
                            value="{{ $value }}"
                        >

                    @endif

                @endforeach


                <div class="space-y-4 p-5">


                    {{-- NAMA --}}
                    <div>

                        <label class="mb-1 block text-xs text-gray-500">
                            Nama Potongan
                        </label>

                        <input
                            type="text"
                            id="editNamaPotongan"
                            name="nama_potongan"
                            required
                            class="w-full rounded-lg border-gray-200 py-2 text-sm focus:border-gray-400 focus:ring-0"
                        >

                    </div>


                    {{-- NOMINAL --}}
                    <div>

                        <label class="mb-1 block text-xs text-gray-500">
                            Nominal
                        </label>

                        <div class="relative">

                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                Rp
                            </span>

                            <input
                                type="text"
                                id="editNominal"
                                name="nominal"
                                required
                                class="rupiah-input w-full rounded-lg border-gray-200 py-2 pl-9 text-sm focus:border-gray-400 focus:ring-0"
                                placeholder="0"
                                inputmode="numeric"
                                autocomplete="off"
                            >

                        </div>

                    </div>


                    {{-- KETERANGAN --}}
                    <div>

                        <label class="mb-1 block text-xs text-gray-500">
                            Keterangan
                        </label>

                        <textarea
                            id="editKeterangan"
                            name="keterangan"
                            rows="2"
                            class="w-full rounded-lg border-gray-200 py-2 text-sm focus:border-gray-400 focus:ring-0"
                        ></textarea>

                    </div>

                </div>


                <div class="flex justify-end gap-2 border-t border-gray-100 px-5 py-4">

                    <button
                        type="button"
                        onclick="closeEditDeduction()"
                        class="rounded-lg border border-gray-200 px-4 py-2 text-xs font-medium text-gray-600"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-xs font-medium text-white"
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<div id="addAdditionModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30" onclick="closeAddAddition()"></div>
    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md rounded-xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                <h2 class="text-sm font-semibold text-gray-800">Tambah Nominal Tambahan</h2>
                <button type="button" onclick="closeAddAddition()" class="text-gray-400 hover:text-gray-700">×</button>
            </div>
            <form action="{{ route('salary.addition.store', $employee->id) }}" method="POST">
                @csrf
                @foreach($queryFilters as $key => $value)
                    @if($value !== null && $value !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <div class="space-y-4 p-5">
                    <div>
                        <label class="mb-1 block text-xs text-gray-500">Nama Tambahan</label>
                        <input type="text" name="nama_tambahan" required placeholder="Contoh: Bonus"
                               class="w-full rounded-lg border-gray-200 py-2 text-sm focus:border-gray-400 focus:ring-0">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs text-gray-500">Nominal</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rp</span>
                            <input type="text" name="nominal" required class="rupiah-input w-full rounded-lg border-gray-200 py-2 pl-9 text-sm"
                                   placeholder="0" inputmode="numeric" autocomplete="off">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs text-gray-500">Keterangan</label>
                        <textarea name="keterangan_tambahan" rows="2" placeholder="Opsional"
                                  class="w-full rounded-lg border-gray-200 py-2 text-sm focus:border-gray-400 focus:ring-0"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 border-t border-gray-100 px-5 py-4">
                    <button type="button" onclick="closeAddAddition()" class="rounded-lg border border-gray-200 px-4 py-2 text-xs font-medium text-gray-600">Batal</button>
                    <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-xs font-medium text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editAdditionModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30" onclick="closeEditAddition()"></div>
    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md rounded-xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                <h2 class="text-sm font-semibold text-gray-800">Edit Nominal Tambahan</h2>
                <button type="button" onclick="closeEditAddition()" class="text-gray-400 hover:text-gray-700">×</button>
            </div>
            <form id="editAdditionForm" method="POST">
                @csrf
                @method('PUT')
                @foreach($queryFilters as $key => $value)
                    @if($value !== null && $value !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <div class="space-y-4 p-5">
                    <div>
                        <label class="mb-1 block text-xs text-gray-500">Nama Tambahan</label>
                        <input id="editNamaTambahan" type="text" name="nama_tambahan" required
                               class="w-full rounded-lg border-gray-200 py-2 text-sm focus:border-gray-400 focus:ring-0">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs text-gray-500">Nominal</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rp</span>
                            <input id="editNominalTambahan" type="text" name="nominal" required class="rupiah-input w-full rounded-lg border-gray-200 py-2 pl-9 text-sm"
                                   placeholder="0" inputmode="numeric" autocomplete="off">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs text-gray-500">Keterangan</label>
                        <textarea id="editKeteranganTambahan" name="keterangan_tambahan" rows="2"
                                  class="w-full rounded-lg border-gray-200 py-2 text-sm focus:border-gray-400 focus:ring-0"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 border-t border-gray-100 px-5 py-4">
                    <button type="button" onclick="closeEditAddition()" class="rounded-lg border border-gray-200 px-4 py-2 text-xs font-medium text-gray-600">Batal</button>
                    <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-xs font-medium text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- =============================================================== --}}
{{-- JAVASCRIPT --}}
{{-- =============================================================== --}}

<script>

    function formatRupiah(value) {

        const digits = String(value ?? '').replace(/\D/g, '');

        if (!digits) {
            return '';
        }

        return new Intl.NumberFormat('id-ID').format(
            Number(digits)
        );
    }


    function cleanRupiah(value) {

        return String(value ?? '').replace(/\D/g, '');

    }


    function initRupiahInputs() {

        document
            .querySelectorAll('.rupiah-input')
            .forEach(function (input) {

                input.addEventListener('input', function () {

                    this.value = formatRupiah(
                        this.value
                    );

                });


                if (input.value) {

                    input.value = formatRupiah(
                        input.value
                    );

                }

            });

    }


    document.addEventListener(
        'DOMContentLoaded',
        function () {

            initRupiahInputs();


            document
                .querySelectorAll('form')
                .forEach(function (form) {

                    form.addEventListener(
                        'submit',
                        function () {

                            form
                                .querySelectorAll('.rupiah-input')
                                .forEach(function (input) {

                                    input.value = cleanRupiah(
                                        input.value
                                    );

                                });

                        }
                    );

                });

        }
    );


    function openAddDeduction() {

        const modal = document.getElementById(
            'addDeductionModal'
        );

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');

        document.body.classList.add(
            'overflow-hidden'
        );


        const nominal = modal.querySelector(
            '.rupiah-input'
        );

        if (nominal) {

            nominal.value = '';

            nominal.focus();

        }

    }


    function closeAddDeduction() {

        const modal = document.getElementById(
            'addDeductionModal'
        );

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');

        document.body.classList.remove(
            'overflow-hidden'
        );

    }


    function openEditDeduction(
        id,
        nama,
        nominal,
        keterangan
    ) {

        const modal = document.getElementById(
            'editDeductionModal'
        );

        const form = document.getElementById(
            'editDeductionForm'
        );

        if (!modal || !form) {
            return;
        }


        document.getElementById(
            'editNamaPotongan'
        ).value = nama ?? '';


        document.getElementById(
            'editNominal'
        ).value = formatRupiah(
            nominal
        );


        document.getElementById(
            'editKeterangan'
        ).value = keterangan ?? '';


        form.action =
            "{{ url('/gaji/' . $employee->id . '/detail/potongan') }}/"
            + id;


        modal.classList.remove('hidden');

        document.body.classList.add(
            'overflow-hidden'
        );


        document.getElementById(
            'editNamaPotongan'
        ).focus();

    }


    function closeEditDeduction() {

        const modal = document.getElementById(
            'editDeductionModal'
        );

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');

        document.body.classList.remove(
            'overflow-hidden'
        );

    }

    function openAddAddition() {
        const modal = document.getElementById('addAdditionModal');
        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        const nominal = modal.querySelector('.rupiah-input');
        if (nominal) {
            nominal.value = '';
            nominal.focus();
        }
    }

    function closeAddAddition() {
        const modal = document.getElementById('addAdditionModal');
        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openEditAddition(id, nama, nominal, keterangan) {
        const modal = document.getElementById('editAdditionModal');
        const form = document.getElementById('editAdditionForm');
        if (!modal || !form) {
            return;
        }

        document.getElementById('editNamaTambahan').value = nama ?? '';
        document.getElementById('editNominalTambahan').value = formatRupiah(nominal);
        document.getElementById('editKeteranganTambahan').value = keterangan ?? '';
        form.action = "{{ url('/gaji/' . $employee->id . '/detail/tambahan') }}/" + id;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        document.getElementById('editNamaTambahan').focus();
    }

    function closeEditAddition() {
        const modal = document.getElementById('editAdditionModal');
        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }


    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Escape') {

                closeAddDeduction();

                closeEditDeduction();

                closeAddAddition();

                closeEditAddition();

            }

        }
    );

</script>

@endsection