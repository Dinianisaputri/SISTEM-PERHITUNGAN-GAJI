@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="soft-card p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <span class="mini-tag">
                    Data Karyawan
                </span>

                <h1 class="mt-3 text-3xl font-bold text-slate-800">
                    Kelola Karyawan
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola data seluruh karyawan PT Starfood International.
                </p>
            </div>

            <a
                href="{{ route('employees.create') }}"
                class="primary-btn"
            >
                + Tambah Karyawan
            </a>

        </div>
    </div>


    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- STATISTIK --}}
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

        <div class="soft-card p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                Total Karyawan
            </p>

            <div class="mt-4 text-3xl font-bold text-blue-700">
                {{ $totalKaryawan }}
            </div>
        </div>


        <div class="soft-card p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                Karyawan Aktif
            </p>

            <div class="mt-4 text-3xl font-bold text-emerald-600">
                {{ $karyawanAktif }}
            </div>
        </div>


        <div class="soft-card p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                Karyawan Nonaktif
            </p>

            <div class="mt-4 text-3xl font-bold text-slate-600">
                {{ $karyawanNonaktif }}
            </div>
        </div>


        <div class="soft-card p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                Karyawan Borongan
            </p>

            <div class="mt-4 text-3xl font-bold text-blue-700">
                {{ $karyawanBorongan }}
            </div>
        </div>

    </div>


    {{-- FILTER --}}
    <div class="soft-card overflow-hidden">

        <div class="border-b border-slate-200 p-5">

            <form
                method="GET"
                action="{{ route('employees.index') }}"
                class="grid gap-3 md:grid-cols-2 xl:grid-cols-5"
            >

                {{-- SEARCH --}}
                <div class="xl:col-span-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, PIN, NIK, bagian..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- BAGIAN --}}
                <div>

                    <select
                        name="bagian"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            Semua Bagian
                        </option>

                        @foreach($bagianList as $bagian)

                            <option
                                value="{{ $bagian }}"
                                {{ request('bagian') == $bagian ? 'selected' : '' }}
                            >
                                {{ $bagian }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- JENIS --}}
                <div>

                    <select
                        name="jenis_karyawan"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            Semua Jenis
                        </option>

                        <option
                            value="Borongan"
                            {{ request('jenis_karyawan') == 'Borongan' ? 'selected' : '' }}
                        >
                            Borongan
                        </option>

                        <option
                            value="Bulanan"
                            {{ request('jenis_karyawan') == 'Bulanan' ? 'selected' : '' }}
                        >
                            Bulanan
                        </option>

                        <option
                            value="Harian"
                            {{ request('jenis_karyawan') == 'Harian' ? 'selected' : '' }}
                        >
                            Harian
                        </option>

                    </select>

                </div>


                {{-- STATUS --}}
                <div>

                    <select
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="aktif"
                            {{ request('status') == 'aktif' ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="nonaktif"
                            {{ request('status') == 'nonaktif' ? 'selected' : '' }}
                        >
                            Nonaktif
                        </option>

                    </select>

                </div>


                {{-- BUTTON --}}
                <div class="md:col-span-2 xl:col-span-5 flex gap-2">

                    <button
                        type="submit"
                        class="primary-btn"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('employees.index') }}"
                        class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-200"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto p-5">

            <table class="min-w-full text-left text-sm text-slate-700">

                <thead>

                    <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">

                        <th class="px-3 py-3 font-semibold">
                            No
                        </th>

                        <th class="px-3 py-3 font-semibold">
                            PIN
                        </th>

                        <th class="px-3 py-3 font-semibold">
                            Nama
                        </th>

                        <th class="px-3 py-3 font-semibold">
                            No. Induk
                        </th>

                        <th class="px-3 py-3 font-semibold">
                            Jabatan
                        </th>

                        <th class="px-3 py-3 font-semibold">
                            Bagian
                        </th>

                        <th class="px-3 py-3 font-semibold">
                            Jenis
                        </th>

                        <th class="px-3 py-3 font-semibold">
                            Status
                        </th>

                        <th class="px-3 py-3 font-semibold">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($employees as $employee)

                        <tr class="border-b border-slate-100 hover:bg-slate-50/80">

                            {{-- NO --}}
                            <td class="px-3 py-3">
                                {{ $employees->firstItem() + $loop->index }}
                            </td>
                            {{-- PIN --}}
                            <td class="px-3 py-3">

                                @if($employee->fingerprint_pin)

                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                        {{ $employee->fingerprint_pin }}
                                    </span>

                                @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- NAMA --}}
                            <td class="px-3 py-3 font-medium text-slate-800">
                                {{ $employee->name }}
                            </td>


                            {{-- NO INDUK --}}
                            <td class="px-3 py-3">
                                {{ $employee->no_induk_karyawan ?? '-' }}
                            </td>


                            {{-- JABATAN --}}
                            <td class="px-3 py-3">
                                {{ $employee->jabatan ?? '-' }}
                            </td>


                            {{-- BAGIAN --}}
                            <td class="px-3 py-3">
                                {{ $employee->bagian ?? '-' }}
                            </td>


                            {{-- JENIS --}}
                            <td class="px-3 py-3">

                                @if($employee->jenis_karyawan)

                                    <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">
                                        {{ $employee->jenis_karyawan }}
                                    </span>

                                @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td class="px-3 py-3">

                                @if($employee->is_active)

                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                        Aktif
                                    </span>

                                @else

                                    <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700">
                                        Nonaktif
                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td class="px-3 py-3">

                                <div class="flex flex-wrap gap-2">

                                   <a
    href="{{ route('employees.show', $employee) }}"
    class="rounded-lg bg-sky-100 px-2.5 py-1.5 text-xs font-medium text-sky-700 hover:bg-sky-200"
>
    Detail
</a>

                                    <a
                                        href="{{ route('employees.edit', $employee) }}"
                                        class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-200"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('employees.destroy', $employee) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus data karyawan ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-rose-100 px-2.5 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-200"
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
                                colspan="10"
                                class="px-3 py-10 text-center text-slate-500"
                            >
                                Belum ada data karyawan.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        <div class="border-t border-slate-200 px-5 py-4">

            {{ $employees->links() }}

        </div>

    </div>

</div>

@endsection