
@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-[1400px] px-4 py-8 sm:px-6">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Surat Teguran
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola surat teguran karyawan PT Starfood International.
            </p>
        </div>

        <a href="{{ route('warning-notices.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
            + Buat Surat Teguran
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- DAFTAR SURAT --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- JUDUL DAN PENCARIAN --}}
        <div class="border-b border-slate-100 px-5 py-4">
            <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
                <div>
                    <h2 class="font-semibold text-slate-800">
                        Daftar Surat Teguran
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Data surat teguran yang telah dicatat dalam sistem.
                    </p>
                </div>

                <div class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">

                    {{-- PENCARIAN --}}
                    <div class="relative w-full sm:w-64">
                        <input
                            type="search"
                            id="search-teguran"
                            placeholder="Cari nama atau NIK..."
                            class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                        >

                        <svg
                            class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="11" cy="11" r="7"></circle>
                            <path stroke-linecap="round" d="m20 20-4-4"></path>
                        </svg>
                    </div>

                    {{-- FILTER KEPALA UNIT --}}
                    <select
                        id="filter-kepala-unit"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 sm:w-56"
                    >
                        <option value="">Semua Kepala Unit</option>

                        @foreach ($warningNotices->getCollection()->pluck('nama_kepala_unit')->filter()->unique()->sort() as $kepalaUnit)
                            <option value="{{ strtolower($kepalaUnit) }}">
                                {{ $kepalaUnit }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="button"
                        id="reset-filter-teguran"
                        class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1150px] text-left text-sm">

                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">No.</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Nomor Surat</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Tanggal Surat</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">NIK</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Nama Karyawan</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Mulai Berlaku</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Akhir Berlaku</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Kepala Unit</th>
                        <th class="whitespace-nowrap px-4 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tabel-teguran" class="text-slate-700">
                    @forelse ($warningNotices as $notice)
                        <tr
                            class="baris-teguran transition hover:bg-slate-50"
                            data-nama="{{ strtolower($notice->employee->name ?? '') }}"
                            data-nik="{{ strtolower($notice->employee->no_induk_karyawan ?? '') }}"
                            data-kepala-unit="{{ strtolower($notice->nama_kepala_unit ?? '') }}"
                        >
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $warningNotices->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-800">
                                {{ $notice->nomor_surat }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $notice->tanggal_surat?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $notice->employee->no_induk_karyawan ?? '-' }}
                            </td>

                            <td class="px-4 py-4 font-medium text-slate-800">
                                {{ $notice->employee->name ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $notice->tanggal_mulai_berlaku?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $notice->tanggal_akhir_berlaku?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $notice->nama_kepala_unit ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('warning-notices.print', $notice) }}"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                        Unduh PDF
                                    </a>
                                    <a href="{{ route('warning-notices.show', $notice) }}"
                                       class="rounded-lg border border-emerald-200 bg-white px-3 py-1.5 text-xs font-medium text-emerald-700 transition hover:bg-emerald-50">
                                        Detail
                                    </a>

                                    <a href="{{ route('warning-notices.edit', $notice) }}"
                                       class="rounded-lg border border-amber-200 bg-white px-3 py-1.5 text-xs font-medium text-amber-700 transition hover:bg-amber-50">
                                        Edit
                                    </a>

                                    <form action="{{ route('warning-notices.destroy', $notice) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus Surat Teguran ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center">
                                <p class="font-medium text-slate-600">
                                    Belum ada data Surat Teguran.
                                </p>
                                <p class="mt-1 text-sm text-slate-400">
                                    Klik tombol Buat Surat Teguran untuk menambahkan data.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="teguran-hasil-kosong" style="display: none;">
                        <td colspan="9" class="px-4 py-12 text-center">
                            <p class="font-medium text-slate-600">
                                Data tidak ditemukan.
                            </p>
                            <p class="mt-1 text-sm text-slate-400">
                                Coba ubah kata kunci pencarian atau filter kepala unit.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($warningNotices->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $warningNotices->links() }}
            </div>
        @endif
    </div>
</div>

{{-- PENCARIAN DAN FILTER --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('search-teguran');
    const filter = document.getElementById('filter-kepala-unit');
    const reset = document.getElementById('reset-filter-teguran');
    const rows = Array.from(document.querySelectorAll('.baris-teguran'));
    const emptyRow = document.getElementById('teguran-hasil-kosong');

    function filterTeguran() {
        const keyword = search.value.trim().toLowerCase();
        const kepalaUnit = filter.value;
        let visible = 0;

        rows.forEach(function (row) {
            const nama = row.dataset.nama || '';
            const nik = row.dataset.nik || '';
            const kepala = row.dataset.kepalaUnit || '';

            const cocokSearch = nama.includes(keyword) || nik.includes(keyword);
            const cocokKepala = !kepalaUnit || kepala === kepalaUnit;
            const tampil = cocokSearch && cocokKepala;

            row.style.display = tampil ? '' : 'none';

            if (tampil) visible++;
        });

        if (emptyRow) {
            emptyRow.style.display = visible === 0 ? '' : 'none';
        }
    }

    search.addEventListener('input', filterTeguran);
    filter.addEventListener('change', filterTeguran);

    reset.addEventListener('click', function () {
        search.value = '';
        filter.value = '';
        filterTeguran();
    });

    filterTeguran();
});
</script>
@endsection
