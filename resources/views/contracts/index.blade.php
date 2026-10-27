@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-[1400px] px-4 py-8 sm:px-6">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Surat Kontrak
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola data kontrak kerja karyawan PT Starfood International.
            </p>
        </div>

        <a href="{{ route('contracts.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
            + Tambah Surat Kontrak
        </a>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            {{ session('error') }}
        </div>
    @endif
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        
<div class="border-b border-slate-100 px-5 py-4">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="font-semibold text-slate-800">Daftar Kontrak</h2>
            <p class="mt-1 text-xs text-slate-500">
                Data kontrak yang tersimpan dalam sistem.
            </p>
        </div>

        
<div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
    <div class="relative w-full sm:w-64">
        <input
            type="search"
            id="search-kontrak"
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

    <select
        id="filter-jenis-kontrak"
        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 sm:w-56"
    >
        <option value="">Semua Jenis Kontrak</option>
        <option value="PKWT BO Jam FF">PKWT BO Jam FF</option>
        <option value="PKWT BO Jam FM">PKWT BO Jam FM</option>
        <option value="PKWT BO Jam SR">PKWT BO Jam SR</option>
        <option value="PKWT BO Jam VA">PKWT BO Jam VA</option>
        <option value="PKWT FF">PKWT FF</option>
        <option value="PKWT FM">PKWT FM</option>
        <option value="PKWT Harian">PKWT Harian</option>
        <option value="PKWT Satpam">PKWT Satpam</option>
    </select>
</div>
</div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-4 font-semibold">No.</th>
                        <th class="px-4 py-4 font-semibold">Nomor Kontrak</th>
                        <th class="px-4 py-4 font-semibold">NIK</th>
                        <th class="px-4 py-4 font-semibold">Nama Karyawan</th>
                        <th class="px-4 py-4 font-semibold">Jenis Kontrak</th>
                        <th class="px-4 py-4 font-semibold">Mulai</th>
                        <th class="px-4 py-4 font-semibold">Berakhir</th>
                        <th class="px-4 py-4 font-semibold">Sisa Masa Kontrak</th>
                        <th class="px-4 py-4 font-semibold">Status</th>
                        <th class="px-4 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($contracts as $contract)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $contracts->firstItem() + $loop->index }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-800">
                                {{ $contract->nomor_kontrak }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $contract->employee->no_induk_karyawan ?? '-' }}
                            </td>
                            <td class="px-4 py-4 font-medium">
                                {{ $contract->employee->name ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $contract->jenis_kontrak }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $contract->tanggal_mulai->format('d-m-Y') }}
                            </td>
                            
<td class="whitespace-nowrap px-4 py-4">
    {{ $contract->tanggal_berakhir?->format('d-m-Y') ?? '-' }}
</td>

<td class="whitespace-nowrap px-4 py-4">
    @if (!$contract->tanggal_berakhir)
        <span class="text-slate-500">Tidak ditentukan</span>
    @elseif ($contract->status === 'dibatalkan')
        <span class="text-slate-500">Dibatalkan</span>
    @elseif (now()->startOfDay()->gt($contract->tanggal_berakhir->copy()->startOfDay()))
        <span class="font-medium text-red-700">Lewat</span>
    @else
        @php
            $sisaHari = (int) now()->startOfDay()
                ->diffInDays($contract->tanggal_berakhir->copy()->startOfDay(), false);
        @endphp

        @if ($sisaHari <= 7)
            <span class="font-semibold text-red-700">
                {{ $sisaHari === 0 ? 'Berakhir hari ini' : $sisaHari . ' hari lagi' }}
            </span>
        @elseif ($sisaHari <= 30)
            <span class="font-medium text-amber-700">
                {{ $sisaHari }} hari lagi
            </span>
        @else
            <span class="font-medium text-emerald-700">
                {{ $sisaHari }} hari lagi
            </span>
        @endif
    @endif
</td>

                            <td class="whitespace-nowrap px-4 py-4">
                                @php
                                    $statusClass = match ($contract->status) {
                                        'aktif' => 'bg-emerald-50 text-emerald-700',
                                        'berakhir' => 'bg-slate-100 text-slate-600',
                                        'dibatalkan' => 'bg-red-50 text-red-700',
                                        default => 'bg-amber-50 text-amber-700',
                                    };
                                @endphp

                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($contract->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    
<a href="{{ route('contracts.print', $contract) }}"
   target="_blank"
   class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
    Unduh PDF
</a>

                                    <a href="{{ route('contracts.show', $contract) }}"
                                       class="rounded-lg border border-emerald-200 px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-50">
                                        Detail
                                    </a>

                                    <a href="{{ route('contracts.edit', $contract) }}"
                                       class="rounded-lg border border-amber-200 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50">
                                        Edit
                                    </a>

                                    <form action="{{ route('contracts.destroy', $contract) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data kontrak ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                   @empty
    <tr id="hasil-kosong" style="display: none;">
        <td colspan="10" class="px-4 py-12 text-center">
            <p class="font-medium text-slate-600">
                Belum ada data Surat Kontrak
            </p>
            <p class="mt-1 text-sm text-slate-400">
                Tambahkan kontrak karyawan untuk mulai mengelola data.
            </p>
        </td>
    </tr>
@endforelse
                </tbody>
            </table>
        </div>

        @if ($contracts->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $contracts->links() }}
            </div>
        @endif
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('search-kontrak');
    const filter = document.getElementById('filter-jenis-kontrak');
    const tbody = document.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'))
        .filter(row => row.querySelectorAll('td').length > 1);

    const emptyRow = document.getElementById('hasil-kosong');

    function filterContracts() {
        const keyword = search.value.trim().toLowerCase();
        const jenis = filter.value.toLowerCase();
        let visible = 0;

        rows.forEach(function (row) {
            const cells = row.querySelectorAll('td');
            const text = row.textContent.toLowerCase();

            // Kolom Jenis Kontrak adalah kolom ke-5.
            const jenisKontrak = cells[4]?.textContent.trim().toLowerCase() || '';

            const cocokSearch = text.includes(keyword);
            const cocokJenis = !jenis || jenisKontrak === jenis;

            row.style.display = cocokSearch && cocokJenis ? '' : 'none';

            if (cocokSearch && cocokJenis) visible++;
        });

        if (emptyRow) {
            emptyRow.style.display = visible === 0 ? '' : 'none';
        }
    }

    search.addEventListener('input', filterContracts);
    filter.addEventListener('change', filterContracts);
});
</script>

@endsection
