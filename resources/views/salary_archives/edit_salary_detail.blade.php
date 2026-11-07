@extends('layouts.app')

@section('content')

@php

    $jenis = strtolower(trim((string) ($detail->jenis_karyawan ?? '')));

    $isBorongan = $jenis === 'borongan';

    $isBulanan = $jenis === 'bulanan';

    $isHarian = $jenis === 'harian';

    $jamBulat = fn ($value) => app(
        \App\Services\SalaryCalculationService::class
    )->roundHoursToWhole((float) ($value ?? 0));

$potonganAwal = (float) ($detail->total_potongan ?? 0);

$potonganTambahan = (float) ($detail->potongan_tambahan ?? 0);

$rincianPotonganTambahan = old(

    'potongan',

    $detail->rincian_potongan_tambahan ?? []

);

// Kompatibilitas untuk potongan lama yang belum punya rincian JSON.

if (

    empty($rincianPotonganTambahan)

    && (float) ($detail->potongan_tambahan ?? 0) > 0

) {

    $rincianPotonganTambahan = [[

        'nama_potongan' => 'Potongan tambahan',

        'nominal' => (float) $detail->potongan_tambahan,

        'keterangan' => $detail->alasan_potongan_tambahan ?? '',

    ]];

}

if (!is_array($rincianPotonganTambahan)) {

    $rincianPotonganTambahan = [];

}

if (!is_array($rincianPotonganTambahan)) {

    $rincianPotonganTambahan = [];

}

$gajiKotor = (float) ($detail->gaji_kotor ?? 0);

$gajiBersih = $gajiKotor - $potonganAwal - $potonganTambahan;

    $periodeMulai = $salaryArchive->start_date;

    $periodeSelesai = $salaryArchive->end_date;

@endphp

<div class="min-h-[calc(100vh-64px)] bg-slate-50">

    <div class="mx-auto max-w-[1450px] px-5 py-5">

        {{-- HEADER --}}

        <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

            <div class="flex flex-wrap items-center justify-between gap-4">

                <div class="flex items-center gap-4">

                    <a

                        href="{{ route('salary_archives.show', $salaryArchive->id) }}"

                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100"

                    >

                        <svg xmlns="http://www.w3.org/2000/svg"

                             class="h-4 w-4" fill="none"

                             viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round"

                                  stroke-width="2" d="M15 19l-7-7 7-7"/>

                        </svg>

                    </a>

                    <div>

                        <div class="flex flex-wrap items-center gap-2">

                            <h1 class="text-lg font-semibold text-slate-900">

                                Detail Arsip Gaji

                            </h1>

                            <span class="rounded-full px-2.5 py-1 text-[10px] font-medium uppercase

                                {{ $isBorongan ? 'bg-blue-50 text-blue-700' : ($isBulanan ? 'bg-violet-50 text-violet-700' : 'bg-emerald-50 text-emerald-700') }}">

                                {{ $detail->jenis_karyawan ?: 'Karyawan' }}

                            </span>

                        </div>

                        <p class="mt-1 text-sm text-slate-500">

                            {{ $detail->employee_name }}

                            <span class="mx-1 text-slate-300">·</span>

                            PIN {{ $detail->fingerprint_pin ?: '-' }}

                            <span class="mx-1 text-slate-300">·</span>

                            {{ $detail->bagian ?: '-' }}

                        </p>

                    </div>

                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">

                    <p class="text-[10px] font-medium uppercase tracking-wider text-slate-400">

                        Periode Arsip

                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-700">

                        {{ \Carbon\Carbon::parse($periodeMulai)->format('d M Y') }}

                        — {{ \Carbon\Carbon::parse($periodeSelesai)->format('d M Y') }}

                    </p>

                </div>

            </div>

        </div>

        {{-- ALERT --}}

        @if ($errors->any())

            <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">

                <ul class="list-disc pl-5">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        @if (session('success'))

            <div class="mb-4 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">

                {{ session('success') }}

            </div>

        @endif

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1.55fr_1fr]">

            {{-- KIRI: STATISTIK SNAPSHOT --}}

            <div class="space-y-5">

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 md:grid-cols-4 md:divide-y-0">

                        <div class="px-5 py-4">

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">

                                Hari Hadir

                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">

                                {{ number_format((float) $detail->hari_hadir, 0, ',', '.') }}

                                <span class="text-xs font-medium text-slate-400">hari</span>

                            </p>

                        </div>

                        <div class="px-5 py-4">

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">

                                Jam Kerja

                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">

                                {{ number_format($jamBulat($detail->jam_kerja), 0, ',', '.') }}

                                <span class="text-xs font-medium text-slate-400">jam</span>

                            </p>

                        </div>

                        <div class="px-5 py-4">

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">

                                Lembur Biasa

                            </p>

                            <p class="mt-2 text-xl font-bold text-slate-900">

                                {{ number_format($jamBulat($detail->jam_lembur_biasa), 0, ',', '.') }}

                                <span class="text-xs text-slate-400">jam</span>

                            </p>

                        </div>

                        <div class="px-5 py-4">

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">

                                Lembur Libur

                            </p>

                            <p class="mt-2 text-xl font-bold text-orange-600">

                                {{ number_format($jamBulat($detail->jam_lembur_libur), 0, ',', '.') }}

                                <span class="text-xs text-slate-400">jam</span>

                            </p>

                        </div>

                    </div>

                </div>

                {{-- KETERANGAN SNAPSHOT --}}

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <h2 class="text-sm font-semibold text-slate-800">

                        Ringkasan Data Arsip

                    </h2>

                    <p class="mt-1 text-xs text-slate-400">

                        Data ini berasal dari snapshot saat arsip gaji dibuat.

                    </p>

                    <div class="mt-5 divide-y divide-slate-100">

                        <div class="flex justify-between gap-4 py-3 text-sm">

                            <span class="text-slate-500">Jenis Karyawan</span>

                            <span class="font-medium text-slate-800">

                                {{ $detail->jenis_karyawan ?: '-' }}

                            </span>

                        </div>

                        <div class="flex justify-between gap-4 py-3 text-sm">

                            <span class="text-slate-500">Tarif Harian</span>

                            <span class="font-medium text-slate-800">

                                Rp {{ number_format((float) $detail->tarif_harian, 0, ',', '.') }}

                            </span>

                        </div>

                        <div class="flex justify-between gap-4 py-3 text-sm">

                            <span class="text-slate-500">Tarif Per Jam</span>

                            <span class="font-medium text-slate-800">

                                Rp {{ number_format((float) $detail->tarif_jam, 0, ',', '.') }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

            {{-- KANAN: PERHITUNGAN GAJI --}}

            <div class="space-y-5">

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-100 px-5 py-4">

                        <h2 class="text-sm font-semibold text-slate-800">

                            Perhitungan Gaji

                        </h2>

                        <p class="mt-1 text-xs text-slate-400">

                            Ringkasan sesuai arsip tersimpan.

                        </p>

                    </div>

                    <div class="space-y-1 px-5 py-4">

                        <div class="flex items-center justify-between gap-4 py-2">

                            <span class="text-sm text-slate-600">Gaji Pokok</span>

                            <span class="text-sm font-medium text-slate-800">

                                Rp {{ number_format((float) $detail->gaji_pokok, 0, ',', '.') }}

                            </span>

                        </div>

                        <div class="flex items-center justify-between gap-4 py-2">

                            <div>

                                <span class="text-sm text-slate-600">Total Lembur</span>

                                <p class="text-[10px] text-slate-400">

                                    {{ number_format($jamBulat($detail->jam_lembur), 0, ',', '.') }} jam

                                </p>

                            </div>

                            <span class="text-sm font-medium text-slate-800">

                                Rp {{ number_format((float) $detail->total_lembur, 0, ',', '.') }}

                            </span>

                        </div>

                        <div class="flex items-center justify-between gap-4 py-2">

                            <span class="text-sm text-slate-600">Uang Makan</span>

                            <span class="text-sm font-medium text-slate-800">

                                Rp {{ number_format((float) $detail->uang_makan_lembur, 0, ',', '.') }}

                            </span>

                        </div>

                        <div class="mt-3 border-t border-slate-100 pt-4">

                            <div class="flex items-center justify-between gap-4">

                                <span class="text-sm font-semibold text-slate-700">Gaji Kotor</span>

                                <span class="text-sm font-bold text-slate-900">

                                    Rp {{ number_format($gajiKotor, 0, ',', '.') }}

                                </span>

                            </div>

                        </div>

{{-- POTONGAN --}}

<div class="mt-5 rounded-xl bg-slate-50 p-3">

    <div class="mb-3 flex items-center justify-between gap-3">

        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">

            Rincian Potongan

        </p>

        <button

            type="button"

            onclick="tambahPotongan()"

            class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100"

        >

            + Tambah Potongan

        </button>

    </div>

    <div class="flex items-center justify-between gap-3 py-2">

        <span class="text-sm text-slate-600">Potongan Awal</span>

        <span class="text-sm font-semibold text-red-600">

            −Rp {{ number_format($potonganAwal, 0, ',', '.') }}

        </span>

    </div>

    <form

        action="{{ route('salary_archives.details.update', [

            'salaryArchive' => $salaryArchive->id,

            'detail' => $detail->id,

        ]) }}"

        method="POST"

    >

        @csrf

        @method('PUT')

         <div id="daftar-potongan" class="space-y-3">

            @foreach ($rincianPotonganTambahan as $index => $potongan)

                <div class="potongan-item rounded-lg border border-slate-200 bg-white p-3">

                    <div class="mb-3 flex items-center justify-between">

                        <span class="text-xs font-semibold text-slate-600">

                            Potongan Tambahan

                        </span>

                        <button

                            type="button"

                            onclick="hapusPotongan(this)"

                            class="text-xs font-medium text-red-600 hover:text-red-800"

                        >

                            Hapus

                        </button>

                    </div>

                    <div class="space-y-3">

                        <div>

                            <label class="mb-1 block text-xs font-medium text-slate-600">

                                Nama Potongan

                            </label>

                            <input

                                type="text"

                                name="potongan[{{ $index }}][nama_potongan]"

                                value="{{ $potongan['nama_potongan'] ?? '' }}"

                                maxlength="255"

                                required

                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"

                                placeholder="Contoh: Kasbon"

                            >

                        </div>

                        <div>

                            <label class="mb-1 block text-xs font-medium text-slate-600">

                                Nominal

                            </label>

                            <input

                                type="number"

                                name="potongan[{{ $index }}][nominal]"

                                value="{{ $potongan['nominal'] ?? 0 }}"

                                min="0"

                                step="0.01"

                                required

                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"

                                placeholder="0"

                            >

                        </div>

                        <div>

                            <label class="mb-1 block text-xs font-medium text-slate-600">

                                Keterangan

                            </label>

                            <textarea

                                name="potongan[{{ $index }}][keterangan]"

                                rows="2"

                                maxlength="1000"

                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"

                                placeholder="Keterangan potongan (opsional)"

                            >{{ $potongan['keterangan'] ?? '' }}</textarea>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

        <p id="potongan-kosong" class="hidden rounded-lg border border-dashed border-slate-300 p-4 text-center text-xs text-slate-500">

            Belum ada potongan tambahan.

        </p>

        <div class="border-t border-slate-200 pt-3">

            <div class="flex items-center justify-between gap-3">

                <span class="text-sm font-medium text-slate-600">

                    Total Potongan

                </span>

                <span class="text-sm font-semibold text-red-600">

                    −Rp <span id="total-potongan-tampilan">0</span>

                </span>

            </div>

        </div>

        <div class="flex justify-end gap-2 pt-1">

            <a

                href="{{ route('salary_archives.show', $salaryArchive->id) }}"

                class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50"

            >

                Batal

            </a>

            <button

                type="submit"

                class="rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white hover:bg-slate-800">

                Simpan Perubahan

            </button>

        </div>
    </form>

<script>

    let indeksPotongan = {{ count($rincianPotonganTambahan) }};

    function tambahPotongan() {

        const daftar = document.getElementById('daftar-potongan');

        const index = indeksPotongan++;

        const item = document.createElement('div');

        item.className = 'potongan-item rounded-lg border border-slate-200 bg-white p-3';

        item.innerHTML = `

            <div class="mb-3 flex items-center justify-between">

                <span class="text-xs font-semibold text-slate-600">

                    Potongan Tambahan

                </span>

                <button

                    type="button"

                    class="text-xs font-medium text-red-600 hover:text-red-800"

                >

                    Hapus

                </button>

            </div>

            <div class="space-y-3">

                <div>

                    <label class="mb-1 block text-xs font-medium text-slate-600">

                        Nama Potongan

                    </label>

                    <input

                        type="text"

                        name="potongan[${index}][nama_potongan]"

                        maxlength="255"

                        required

                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"

                        placeholder="Contoh: Kasbon"

                    >

                </div>

                <div>

                    <label class="mb-1 block text-xs font-medium text-slate-600">

                        Nominal

                    </label>

                    <input

                        type="number"

                        name="potongan[${index}][nominal]"

                        value="0"

                        min="0"

                        step="0.01"

                        required

                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"

                    >

                </div>

                <div>

                    <label class="mb-1 block text-xs font-medium text-slate-600">

                        Keterangan

                    </label>

                    <textarea

                        name="potongan[${index}][keterangan]"

                        rows="2"

                        maxlength="1000"

                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"

                        placeholder="Keterangan potongan (opsional)"

                    ></textarea>

                </div>

            </div>

        `;

        item.querySelector('button').addEventListener('click', function () {

            hapusPotongan(this);

        });

        daftar.appendChild(item);

        perbaruiTotalPotongan();

    }

    function hapusPotongan(button) {

        button.closest('.potongan-item').remove();

        perbaruiTotalPotongan();

    }

    function perbaruiTotalPotongan() {

        const daftar = document.querySelectorAll(

            '#daftar-potongan input[name$="[nominal]"]'

        );

        let total = 0;

        daftar.forEach(function (input) {

            total += Math.max(0, Number(input.value) || 0);

        });

        document.getElementById('total-potongan-tampilan').textContent =

            Math.round(total).toLocaleString('id-ID');

        document.getElementById('potongan-kosong').classList.toggle(

            'hidden',

            document.querySelectorAll('#daftar-potongan .potongan-item').length > 0

        );

    }

    document.getElementById('daftar-potongan').addEventListener('input', function (event) {

        if (event.target.matches('input[name$="[nominal]"]')) {

            perbaruiTotalPotongan();

        }

    });

    perbaruiTotalPotongan();

</script>

                        {{-- GAJI BERSIH --}}

                        <div class="mt-5 rounded-xl bg-gradient-to-r from-slate-900 to-slate-700 p-4 text-white">

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-300">

                                Gaji Bersih

                            </p>

                            <p class="mt-2 text-2xl font-bold">

                                Rp {{ number_format($gajiBersih, 0, ',', '.') }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
