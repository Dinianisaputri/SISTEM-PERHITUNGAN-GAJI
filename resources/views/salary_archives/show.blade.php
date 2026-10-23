@extends('layouts.app')

@section('title', 'Detail Arsip Gaji')

@section('content')

<div class="space-y-6">

    {{-- ============================= --}}
    {{-- HEADER --}}
    {{-- ============================= --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-sm font-medium text-blue-600">
                GAJI KARYAWAN
            </p>

            <h1 class="mt-1 text-2xl font-bold text-slate-800">
                Detail Arsip Gaji
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Detail data perhitungan gaji karyawan borongan.
            </p>
        </div>

        <a
            href="{{ route('salary_archives.index') }}"
            class="inline-flex w-fit items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
        >
            ← Kembali
        </a>

    </div>


    {{-- ============================= --}}
    {{-- INFORMASI ARSIP --}}
    {{-- ============================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5">
            <h3 class="text-base font-semibold text-slate-800">
                Informasi Arsip
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Informasi periode dan jumlah data dalam arsip gaji.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- NAMA ARSIP --}}
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Nama Arsip
                </p>

                <p class="mt-2 font-semibold text-slate-800">
                    {{ $salaryArchive->archive_name }}
                </p>
            </div>


            {{-- PERIODE --}}
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Periode
                </p>

                <p class="mt-2 font-semibold text-slate-800">
                    {{ $salaryArchive->start_date->format('d/m/Y') }}
                    <span class="font-normal text-slate-500">s/d</span>
                    {{ $salaryArchive->end_date->format('d/m/Y') }}
                </p>
            </div>


            {{-- TOTAL KARYAWAN --}}
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Total Karyawan
                </p>

                <p class="mt-2 font-semibold text-slate-800">
                    {{ $salaryArchive->total_employees }}
                    <span class="font-normal text-slate-500">orang</span>
                </p>
            </div>


            {{-- TOTAL CHECKLOG --}}
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Total Checklog
                </p>

                <p class="mt-2 font-semibold text-slate-800">
                    {{ $salaryArchive->total_checklogs }}
                    <span class="font-normal text-slate-500">data</span>
                </p>
            </div>

        </div>

    </div>


    {{-- ============================= --}}
    {{-- TOTAL GAJI --}}
    {{-- ============================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div>
            <p class="text-sm font-medium text-slate-500">
                Total Gaji Arsip
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                Rp {{ number_format((float) $salaryArchive->total_salary, 0, ',', '.') }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Total gaji berdasarkan data yang tersimpan dalam arsip.
            </p>
        </div>

    </div>


    {{-- ============================= --}}
    {{-- FILTER DATA ARSIP --}}
    {{-- ============================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5">
            <h3 class="text-base font-semibold text-slate-800">
                Filter Data
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Gunakan filter untuk mencari data checklog tertentu.
            </p>
        </div>


        <form
            action="{{ route('salary_archives.show', $salaryArchive) }}"
            method="GET"
        >

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">

                {{-- DARI TANGGAL --}}
                <div>
                    <label
                        for="filter_tanggal_mulai"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Dari Tanggal
                    </label>

                    <input
                        type="date"
                        id="filter_tanggal_mulai"
                        name="filter_tanggal_mulai"
                        value="{{ request('filter_tanggal_mulai') }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                {{-- SAMPAI TANGGAL --}}
                <div>
                    <label
                        for="filter_tanggal_selesai"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        id="filter_tanggal_selesai"
                        name="filter_tanggal_selesai"
                        value="{{ request('filter_tanggal_selesai') }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                {{-- NAMA KARYAWAN --}}
                <div class="md:col-span-2 xl:col-span-1">
                    <label
                        for="nama_karyawan"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Nama Karyawan
                    </label>

                    <input
                        type="text"
                        id="nama_karyawan"
                        name="nama_karyawan"
                        value="{{ request('nama_karyawan') }}"
                        placeholder="Cari nama karyawan..."
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                {{-- CARI --}}
                <button
                    type="submit"
                    class="inline-flex h-[42px] items-center justify-center rounded-lg bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Cari
                </button>


                {{-- RESET --}}
                <a
                    href="{{ route('salary_archives.show', $salaryArchive) }}"
                    class="inline-flex h-[42px] items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
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

        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- ============================= --}}
    {{-- DATA CHECKLOG --}}
    {{-- ============================= --}}

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h3 class="text-base font-semibold text-slate-800">
                Data Checklog
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Data kehadiran yang termasuk dalam arsip ini.
            </p>

        </div>


        @if($checklogs->count())

            {{-- TABLE --}}
            <div class="w-full overflow-x-auto">

                <table class="min-w-[1250px] w-full text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                No
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                No. ID
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                Nama
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                Tanggal
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                Jam Masuk
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                Jam Pulang
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                Scan Masuk
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                Scan Pulang
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                Istirahat
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                Total Jam Kerja
                            </th>

                            <th class="px-4 py-3 font-semibold text-slate-600">
                                Gaji
                            </th>

                            <th class="px-4 py-3 text-center font-semibold text-slate-600">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($checklogs as $checklog)

                            <tr class="transition hover:bg-slate-50">

                                {{-- NO --}}
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ $loop->iteration }}
                                </td>


                                {{-- NO ID --}}
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ $checklog->employee->no_id ?? '-' }}
                                </td>


                                {{-- NAMA --}}
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-800">
                                    {{ $checklog->employee->name ?? '-' }}
                                </td>


                                {{-- TANGGAL --}}
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ $checklog->tanggal->format('d/m/Y') }}
                                </td>


                                {{-- JAM MASUK --}}
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ $checklog->jam_masuk ?? '-' }}
                                </td>


                                {{-- JAM PULANG --}}
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ $checklog->jam_pulang ?? '-' }}
                                </td>


                                {{-- SCAN MASUK --}}
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ $checklog->scan_masuk ?? '-' }}
                                </td>


                                {{-- SCAN PULANG --}}
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ $checklog->scan_pulang ?? '-' }}
                                </td>


                                {{-- ISTIRAHAT --}}
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ number_format((float) $checklog->jam_istirahat, 2, '.', '') }}
                                    jam
                                </td>


                                {{-- TOTAL JAM KERJA --}}
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ number_format((float) $checklog->total_jam_kerja, 2, ',', '.') }}
                                    jam
                                </td>


                                {{-- GAJI --}}
                                <td class="whitespace-nowrap px-4 py-3 font-semibold text-slate-800">
                                    Rp {{ number_format((float) $checklog->gaji, 0, ',', '.') }}
                                </td>


                                {{-- AKSI --}}
                                <td class="px-4 py-3">

                                    <div class="flex items-center justify-center gap-2">

                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route(
                                                'salary_archives.editChecklog',
                                                [$salaryArchive, $checklog]
                                            ) }}"
                                            class="inline-flex items-center rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-600"
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
                                                class="inline-flex items-center rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700"
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
                                    class="px-6 py-12 text-center text-sm text-slate-500"
                                >
                                    Tidak ada data checklog yang sesuai dengan filter.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6.586a1 1 0 0 1 .707.293l3.414 3.414A1 1 0 0 1 19 7.414V19a2 2 0 0 1-2 2Z"
                        />
                    </svg>
                </div>

                <p class="mt-4 text-sm font-medium text-slate-700">
                    Tidak ada data checklog
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Tidak ada data yang sesuai dengan filter.
                </p>

            </div>

        @endif

    </div>


    {{-- ============================= --}}
    {{-- FOOTER TOTAL --}}
    {{-- ============================= --}}

    <div class="flex justify-end">

        <div class="rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">

            <span class="text-sm text-slate-500">
                Total Gaji:
            </span>

            <strong class="ml-2 text-lg font-bold text-slate-800">
                Rp {{ number_format((float) $salaryArchive->total_salary, 0, ',', '.') }}
            </strong>

        </div>

    </div>

</div>

@endsection