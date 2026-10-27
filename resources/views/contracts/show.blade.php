@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">

    <div class="mb-6">
        <a href="{{ route('contracts.index') }}"
           class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
            ← Kembali ke Daftar Kontrak
        </a>

        <div class="mt-3 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Surat Kontrak</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Informasi lengkap kontrak kerja karyawan.
                </p>
            </div>

            <a href="{{ route('contracts.edit', $contract) }}"
               class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                Edit Kontrak
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col justify-between gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm text-slate-500">Nomor Kontrak</p>
                <h2 class="mt-1 text-lg font-bold text-slate-800">
                    {{ $contract->nomor_kontrak }}
                </h2>
            </div>

            @php
                $statusClass = match ($contract->status) {
                    'aktif' => 'bg-emerald-100 text-emerald-700',
                    'berakhir' => 'bg-slate-100 text-slate-700',
                    'dibatalkan' => 'bg-red-100 text-red-700',
                    default => 'bg-amber-100 text-amber-700',
                };
            @endphp

            <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                {{ ucfirst($contract->status) }}
            </span>
        </div>

        <div class="p-6">
            <h3 class="mb-4 text-base font-semibold text-slate-800">
                Informasi Karyawan
            </h3>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Nama Karyawan</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $contract->employee->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Nomor Induk Karyawan</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $contract->employee->no_induk_karyawan ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Jabatan</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $contract->employee->jabatan ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Bagian / Departemen</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $contract->bagian ?: ($contract->employee->bagian ?? '-') }}
                    </p>
                </div>
            </div>

            <div class="my-6 border-t border-slate-200"></div>

            <h3 class="mb-4 text-base font-semibold text-slate-800">
                Informasi Kontrak
            </h3>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Jenis Kontrak</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $contract->jenis_kontrak }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Tanggal Mulai</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $contract->tanggal_mulai?->format('d F Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Tanggal Berakhir</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $contract->tanggal_berakhir?->format('d F Y') ?? 'Belum ditentukan' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Terakhir Diperbarui</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $contract->updated_at?->format('d-m-Y H:i') ?? '-' }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm text-slate-500">Keterangan</p>
                    <p class="mt-1 whitespace-pre-line text-slate-800">{{ $contract->keterangan ?: 'Tidak ada keterangan.' }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-between">
            <form action="{{ route('contracts.destroy', $contract) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus data kontrak ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-xl border border-red-200 bg-white px-5 py-3 text-sm font-medium text-red-600 hover:bg-red-50">
                    Hapus Data
                </button>
            </form>

            <a href="{{ route('contracts.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-center text-sm font-medium text-slate-700 hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
