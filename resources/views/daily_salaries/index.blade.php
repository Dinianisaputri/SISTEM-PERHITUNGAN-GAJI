@extends('layouts.app')

@section('title', 'Gaji Harian')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <p class="text-sm font-medium text-blue-600">
            GAJI KARYAWAN
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-800">
            Gaji Harian
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Perhitungan gaji berdasarkan jumlah hari kerja dari data fingerprint.
        </p>
    </div>


    {{-- Filter Periode --}}
    <div class="card">

        <div class="mb-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Periode Gaji
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Pilih periode untuk mengambil data fingerprint karyawan Harian.
            </p>
        </div>

        <form
            action="{{ route('daily_salaries.index') }}"
            method="GET"
            class="grid grid-cols-1 gap-4 md:grid-cols-3"
        >

            <div>
                <label
                    for="periode_mulai"
                    class="mb-1 block text-sm font-medium text-slate-700"
                >
                    Periode Mulai
                </label>

                <input
                    type="date"
                    name="periode_mulai"
                    id="periode_mulai"
                    value="{{ $periodeMulai->format('Y-m-d') }}"
                    class="w-full rounded-lg border-slate-300 text-sm"
                >
            </div>


            <div>
                <label
                    for="periode_selesai"
                    class="mb-1 block text-sm font-medium text-slate-700"
                >
                    Periode Selesai
                </label>

                <input
                    type="date"
                    name="periode_selesai"
                    id="periode_selesai"
                    value="{{ $periodeSelesai->format('Y-m-d') }}"
                    class="w-full rounded-lg border-slate-300 text-sm"
                >
            </div>


            <div class="flex items-end">

                <button
                    type="submit"
                    class="w-full rounded-lg bg-slate-800 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700"
                >
                    Tampilkan Data
                </button>

            </div>

        </form>

    </div>


    {{-- Notifikasi --}}
    @if(session('success'))

        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">

            <ul class="list-disc pl-5 text-sm text-red-700">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Informasi Periode --}}
    <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3">

        <p class="text-sm font-semibold text-blue-800">
            Periode:
            {{ $periodeMulai->format('d/m/Y') }}
            -
            {{ $periodeSelesai->format('d/m/Y') }}
        </p>

        <p class="mt-1 text-xs text-blue-700">
            Hari kerja dihitung berdasarkan tanggal fingerprint yang unik.
            Jika dalam satu tanggal terdapat beberapa scan, tetap dihitung sebagai 1 hari kerja.
        </p>

    </div>


    {{-- Data Karyawan --}}
    <div class="card overflow-hidden">

        <div class="mb-5 flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Data Karyawan Harian
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $dataGaji->count() }} karyawan ditemukan.
                </p>
            </div>

        </div>


        @if($dataGaji->count() > 0)

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                No
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Nama Karyawan
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Bagian
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                PIN Fingerprint
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">
                                Hari Kerja
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">
                                Status Fingerprint
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($dataGaji as $index => $data)

                            @php
                                $employee = $data['employee'];
                                $hariKerja = $data['jumlah_hari_kerja'];
                            @endphp

                            <tr class="hover:bg-slate-50">

                                {{-- No --}}
                                <td class="px-5 py-4 text-sm text-slate-500">
                                    {{ $index + 1 }}
                                </td>


                                {{-- Nama --}}
                                <td class="px-5 py-4">

                                    <div class="font-semibold text-slate-800">
                                        {{ $employee->name }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $employee->no_induk_karyawan ?? '-' }}
                                    </div>

                                </td>


                                {{-- Bagian --}}
                                <td class="px-5 py-4 text-sm text-slate-600">

                                    {{ $employee->bagian ?? '-' }}

                                </td>


                                {{-- PIN --}}
                                <td class="px-5 py-4">

                                    <span class="rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                        {{ $employee->fingerprint_pin ?? '-' }}
                                    </span>

                                </td>


                                {{-- Hari Kerja --}}
                                <td class="px-5 py-4 text-center">

                                    <span class="inline-flex min-w-[45px] justify-center rounded-md bg-green-50 px-3 py-1.5 text-sm font-bold text-green-700">
                                        {{ $hariKerja }}
                                    </span>

                                    <div class="mt-1 text-xs text-slate-400">
                                        hari
                                    </div>

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-4 text-center">

                                    @if($hariKerja > 0)

                                        <span class="rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                            Ada Data
                                        </span>

                                    @else

                                        <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                                            Belum Ada
                                        </span>

                                    @endif

                                </td>


                                {{-- Aksi --}}
                                <td class="px-5 py-4 text-center">

                                    <button
                                        type="button"
                                        onclick="openSalaryModal(
                                            {{ $employee->id }},
                                            @js($employee->name),
                                            {{ $hariKerja }}
                                        )"
                                        class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700"
                                    >
                                        Hitung Gaji
                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="py-12 text-center">

                <h3 class="font-semibold text-slate-800">
                    Belum ada karyawan Harian
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Belum ditemukan karyawan aktif dengan jenis karyawan Harian.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- MODAL HITUNG GAJI --}}
<div
    id="salaryModal"
    class="fixed inset-0 z-50 hidden"
>

    <div
        class="absolute inset-0 bg-black/40"
        onclick="closeSalaryModal()"
    ></div>


    <div class="relative flex min-h-screen items-center justify-center p-4">

        <div class="w-full max-w-2xl rounded-2xl bg-white shadow-xl">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b px-6 py-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Hitung Gaji Harian
                    </h2>

                    <p
                        id="modalEmployeeName"
                        class="mt-1 text-sm text-slate-500"
                    ></p>

                </div>

                <button
                    type="button"
                    onclick="closeSalaryModal()"
                    class="text-xl text-slate-400 hover:text-slate-700"
                >
                    ×
                </button>

            </div>


            <form
                action="{{ route('daily_salaries.store') }}"
                method="POST"
            >

                @csrf

                <input
                    type="hidden"
                    name="employee_id"
                    id="modalEmployeeId"
                >

                <input
                    type="hidden"
                    name="periode_mulai"
                    value="{{ $periodeMulai->format('Y-m-d') }}"
                >

                <input
                    type="hidden"
                    name="periode_selesai"
                    value="{{ $periodeSelesai->format('Y-m-d') }}"
                >


                <div class="space-y-5 p-6">

                    {{-- Hari kerja --}}
                    <div class="rounded-lg bg-green-50 p-4">

                        <p class="text-xs font-medium text-green-700">
                            Jumlah Hari Kerja
                        </p>

                        <div class="mt-1">

                            <span
                                id="modalHariKerja"
                                class="text-3xl font-bold text-green-700"
                            >
                                0
                            </span>

                            <span class="text-sm text-green-700">
                                hari
                            </span>

                        </div>

                    </div>


                    {{-- Upah --}}
                    <div>

                        <label class="mb-1 block text-sm font-medium text-slate-700">
                            Upah Harian
                        </label>

                        <input
                            type="number"
                            name="upah_harian"
                            min="0"
                            step="0.01"
                            required
                            placeholder="Contoh: 127855"
                            class="w-full rounded-lg border-slate-300 text-sm"
                        >

                    </div>


                    {{-- Lembur biasa --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div>

                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Lembur Biasa (jam)
                            </label>

                            <input
                                type="number"
                                name="lembur_biasa"
                                value="0"
                                min="0"
                                step="0.5"
                                class="w-full rounded-lg border-slate-300 text-sm"
                            >

                        </div>


                        <div>

                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Tarif Lembur Biasa
                            </label>

                            <input
                                type="number"
                                name="tarif_lembur_biasa"
                                value="0"
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border-slate-300 text-sm"
                            >

                        </div>

                    </div>


                    {{-- Lembur hari libur --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div>

                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Lembur Hari Libur (jam)
                            </label>

                            <input
                                type="number"
                                name="lembur_hari_libur"
                                value="0"
                                min="0"
                                step="0.5"
                                class="w-full rounded-lg border-slate-300 text-sm"
                            >

                        </div>


                        <div>

                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Tarif Lembur Hari Libur
                            </label>

                            <input
                                type="number"
                                name="tarif_lembur_hari_libur"
                                value="0"
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border-slate-300 text-sm"
                            >

                        </div>

                    </div>


                    {{-- Lembur UM --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div>

                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Lembur UM (jam)
                            </label>

                            <input
                                type="number"
                                name="lembur_um"
                                value="0"
                                min="0"
                                step="0.5"
                                class="w-full rounded-lg border-slate-300 text-sm"
                            >

                        </div>


                        <div>

                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Tarif Lembur UM
                            </label>

                            <input
                                type="number"
                                name="tarif_lembur_um"
                                value="0"
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border-slate-300 text-sm"
                            >

                        </div>

                    </div>


                    {{-- Lain-lain dan potongan --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div>

                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Lain-lain
                            </label>

                            <input
                                type="number"
                                name="lain_lain"
                                value="0"
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border-slate-300 text-sm"
                            >

                        </div>


                        <div>

                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Potongan
                            </label>

                            <input
                                type="number"
                                name="potongan"
                                value="0"
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border-slate-300 text-sm"
                            >

                        </div>

                    </div>

                </div>


                {{-- Footer --}}
                <div class="flex justify-end gap-3 border-t px-6 py-4">

                    <button
                        type="button"
                        onclick="closeSalaryModal()"
                        class="rounded-lg border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    >
                        Simpan Perhitungan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

function openSalaryModal(employeeId, employeeName, hariKerja)
{
    document.getElementById('modalEmployeeId').value = employeeId;

    document.getElementById('modalEmployeeName').textContent =
        employeeName;

    document.getElementById('modalHariKerja').textContent =
        hariKerja;

    document.getElementById('salaryModal')
        .classList
        .remove('hidden');
}


function closeSalaryModal()
{
    document.getElementById('salaryModal')
        .classList
        .add('hidden');
}

</script>

@endsection