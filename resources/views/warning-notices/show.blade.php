@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Surat Teguran</h1>
            <p class="mt-1 text-sm text-slate-500">
                Nomor surat:
                <span class="font-medium text-slate-700">{{ $warningNotice->nomor_surat }}</span>
            </p>
        </div>

<a href="{{ request('return_to', route('warning-notices.index')) }}"
   class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
    {{ request('return_to') ? 'Kembali ke Data Karyawan' : 'Kembali ke daftar Surat Teguran' }}
</a>

            <a href="{{ route('warning-notices.edit', $warningNotice) }}"
               class="rounded-lg border border-amber-200 bg-white px-4 py-2.5 text-sm font-medium text-amber-700 transition hover:bg-amber-50">
                Edit
            </a>

            <a href="{{ route('warning-notices.print', $warningNotice) }}"
               target="_blank"
               class="rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                Cetak PDF
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="font-semibold text-slate-800">Informasi Surat</h2>
            <p class="mt-1 text-sm text-slate-500">Rincian surat teguran dan data karyawan terkait.</p>
        </div>

        <div class="p-6">
            <dl class="divide-y divide-slate-100">
                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">Nomor Surat</dt>
                    <dd class="text-sm font-semibold text-slate-800 sm:col-span-2">{{ $warningNotice->nomor_surat }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">Tanggal Surat</dt>
                    <dd class="text-sm text-slate-800 sm:col-span-2">{{ $warningNotice->tanggal_surat->format('d-m-Y') }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">Nama Karyawan</dt>
                    <dd class="text-sm font-medium text-slate-800 sm:col-span-2">{{ $warningNotice->employee->name ?? '-' }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">NIK</dt>
                    <dd class="text-sm text-slate-800 sm:col-span-2">{{ $warningNotice->employee->no_induk_karyawan ?? '-' }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">Jabatan</dt>
                    <dd class="text-sm text-slate-800 sm:col-span-2">{{ $warningNotice->employee->jabatan ?? '-' }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">Bagian</dt>
                    <dd class="text-sm text-slate-800 sm:col-span-2">{{ $warningNotice->employee->bagian ?? '-' }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">Alasan Pelanggaran</dt>
                    <dd class="whitespace-pre-line text-sm leading-6 text-slate-800 sm:col-span-2">{{ $warningNotice->alasan_pelanggaran }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">Mulai Berlaku</dt>
                    <dd class="text-sm text-slate-800 sm:col-span-2">{{ $warningNotice->tanggal_mulai_berlaku->format('d-m-Y') }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">Akhir Berlaku</dt>
                    <dd class="text-sm text-slate-800 sm:col-span-2">{{ $warningNotice->tanggal_akhir_berlaku->format('d-m-Y') }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">Kepala Unit</dt>
                    <dd class="text-sm text-slate-800 sm:col-span-2">{{ $warningNotice->nama_kepala_unit }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection