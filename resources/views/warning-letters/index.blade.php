
@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-[1400px] px-4 py-8 sm:px-6">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Surat Peringatan
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola surat peringatan karyawan PT Starfood International.
            </p>
        </div>

        <a href="{{ route('warning-letters.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
            + Buat Surat SP
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- DAFTAR SP --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- JUDUL DAN PENCARIAN --}}
        <div class="border-b border-slate-100 px-5 py-4">
            <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
                <div>
                    <h2 class="font-semibold text-slate-800">
                        Daftar Surat Peringatan
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Data surat peringatan yang tersimpan dalam sistem.
                    </p>
                </div>

                <div class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">

                    {{-- CARI NAMA --}}
                    <div class="relative w-full sm:w-64">
                        <input
                            type="search"
                            id="search-sp"
                            placeholder="Cari nama karyawan..."
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

                    {{-- FILTER TINGKAT SP --}}
                    <select
                        id="filter-tingkat-sp"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 sm:w-48"
                    >
                        <option value="">Semua Tingkat SP</option>
                        <option value="1">SP I</option>
                        <option value="2">SP II</option>
                        <option value="3">SP III</option>
                    </select>

                    <button
                        type="button"
                        id="reset-filter-sp"
                        class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- LEGENDA WARNA --}}
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 border-b border-slate-100 px-5 py-3 text-xs text-slate-600">
            <span class="inline-flex items-center gap-2">
                <span class="h-3 w-3 rounded-sm border border-emerald-300 bg-emerald-100"></span>
                SP I
            </span>

            <span class="inline-flex items-center gap-2">
                <span class="h-3 w-3 rounded-sm border border-amber-300 bg-amber-100"></span>
                SP II
            </span>

            <span class="inline-flex items-center gap-2">
                <span class="h-3 w-3 rounded-sm border border-red-300 bg-red-100"></span>
                SP III
            </span>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1150px] text-left text-sm">

                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">No.</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Nomor Surat</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Nama Karyawan</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Tingkat SP</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Mulai Berlaku</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Akhir Berlaku</th>
                        <th class="whitespace-nowrap px-4 py-4 font-semibold">Status</th>
                        <th class="whitespace-nowrap px-4 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tabel-sp" class="text-slate-700">
                    @forelse ($warningLetters as $letter)
                        @php
                            $tingkat = strtoupper(trim((string) $letter->tingkat_sp));

                            $tingkatNormal = match ($tingkat) {
                                '1', 'SP 1', 'SP I' => '1',
                                '2', 'SP 2', 'SP II' => '2',
                                '3', 'SP 3', 'SP III' => '3',
                                default => '',
                            };

                            $labelTingkat = match ($tingkatNormal) {
                                '1' => 'SP I',
                                '2' => 'SP II',
                                '3' => 'SP III',
                                default => $letter->tingkat_sp,
                            };

                            $warnaBaris = match ($tingkatNormal) {
                                '1' => 'bg-emerald-100 hover:bg-emerald-200',
                                '2' => 'bg-amber-100 hover:bg-amber-200',
                                '3' => 'bg-red-100 hover:bg-red-200',
                                default => 'bg-slate-100 hover:bg-slate-200',
                            };

                            $warnaBadge = match ($tingkatNormal) {
                                '1' => 'bg-emerald-200 text-emerald-900',
                                '2' => 'bg-amber-200 text-amber-900',
                                '3' => 'bg-red-200 text-red-900',
                                default => 'bg-slate-200 text-slate-700',
                            };

                            $status = strtolower((string) $letter->status);

                            $warnaStatus = match ($status) {
                                'aktif' => 'bg-emerald-200 text-emerald-900',
                                'berakhir' => 'bg-slate-200 text-slate-700',
                                default => 'bg-amber-200 text-amber-900',
                            };
                        @endphp

                        <tr
                            class="baris-sp transition-colors"
                            data-nama="{{ strtolower($letter->employee->name ?? '') }}"
                            data-tingkat="{{ $tingkatNormal }}"
                        >
                            <td class="{{ $warnaBaris }} whitespace-nowrap px-4 py-4">
                                {{ $warningLetters->firstItem() + $loop->index }}
                            </td>

                            <td class="{{ $warnaBaris }} whitespace-nowrap px-4 py-4 font-medium text-slate-800">
                                {{ $letter->nomor_surat }}
                            </td>

                            <td class="{{ $warnaBaris }} px-4 py-4 font-medium text-slate-800">
                                {{ $letter->employee->name ?? '-' }}
                            </td>

                            <td class="{{ $warnaBaris }} whitespace-nowrap px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $warnaBadge }}">
                                    {{ $labelTingkat }}
                                </span>
                            </td>

                            <td class="{{ $warnaBaris }} whitespace-nowrap px-4 py-4">
                                {{ $letter->tanggal_mulai_berlaku?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td class="{{ $warnaBaris }} whitespace-nowrap px-4 py-4">
                                {{ $letter->tanggal_akhir_berlaku?->format('d-m-Y') ?? 'Belum ditentukan' }}
                            </td>

                            <td class="{{ $warnaBaris }} whitespace-nowrap px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $warnaStatus }}">
                                    {{ ucfirst($letter->status ?? 'Tidak diketahui') }}
                                </span>
                            </td>

                            <td class="{{ $warnaBaris }} whitespace-nowrap px-4 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    <a href="{{ route('warning-letters.print', $letter) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-800">
                                        Cetak PDF
                                    </a>

                                    <a href="{{ route('warning-letters.show', $letter) }}"
                                       class="rounded-lg border border-emerald-300 bg-white px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-50">
                                        Detail
                                    </a>

                                    <a href="{{ route('warning-letters.edit', $letter) }}"
                                       class="rounded-lg border border-amber-300 bg-white px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50">
                                        Edit
                                    </a>

                                    <form action="{{ route('warning-letters.destroy', $letter) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus Surat Peringatan ini? Data yang dihapus tidak dapat dikembalikan.')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-lg border border-red-300 bg-white px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="sp-kosong-database">
                            <td colspan="8" class="px-4 py-12 text-center">
                                <p class="font-medium text-slate-600">
                                    Belum ada data Surat Peringatan.
                                </p>
                                <p class="mt-1 text-sm text-slate-400">
                                    Tambahkan surat peringatan untuk mulai mengelola data.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="sp-hasil-kosong" style="display: none;">
                        <td colspan="8" class="px-4 py-12 text-center">
                            <p class="font-medium text-slate-600">
                                Data tidak ditemukan.
                            </p>
                            <p class="mt-1 text-sm text-slate-400">
                                Coba ubah nama karyawan atau tingkat SP.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($warningLetters->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $warningLetters->links() }}
            </div>
        @endif
    </div>
</div>

{{-- PENCARIAN DAN FILTER DI BROWSER --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('search-sp');
    const filter = document.getElementById('filter-tingkat-sp');
    const reset = document.getElementById('reset-filter-sp');
    const rows = Array.from(document.querySelectorAll('.baris-sp'));
    const emptyRow = document.getElementById('sp-hasil-kosong');

    function filterSP() {
        const keyword = search.value.trim().toLowerCase();
        const tingkat = filter.value;
        let visible = 0;

        rows.forEach(function (row) {
            const nama = row.dataset.nama || '';
            const tingkatSP = row.dataset.tingkat || '';

            const cocokNama = nama.includes(keyword);
            const cocokTingkat = !tingkat || tingkatSP === tingkat;
            const tampil = cocokNama && cocokTingkat;

            row.querySelectorAll('td').forEach(function (cell) {
                cell.classList.toggle('hidden', !tampil);
            });

            row.style.display = tampil ? '' : 'none';

            if (tampil) visible++;
        });

        if (emptyRow) {
            emptyRow.style.display = visible === 0 ? '' : 'none';
        }
    }

    search.addEventListener('input', filterSP);
    filter.addEventListener('change', filterSP);

    reset.addEventListener('click', function () {
        search.value = '';
        filter.value = '';
        filterSP();
    });

    filterSP();
});
</script>
@endsection
