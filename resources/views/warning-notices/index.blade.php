@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-[1400px] px-4 py-8 sm:px-6">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Surat Teguran
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Daftar surat teguran karyawan PT Starfood International.
            </p>
        </div>

        <a href="{{ route('warning-notices.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
            + Buat Surat Teguran
        </a>
    </div>

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

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col justify-between gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-semibold text-slate-800">Daftar Surat</h2>
                <p class="mt-1 text-xs text-slate-500">
                    Data surat teguran yang telah dicatat.
                </p>
            </div>
            <span class="text-sm text-slate-500">
                Total halaman: {{ $warningNotices->count() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-4 font-semibold">No.</th>
                        <th class="px-4 py-4 font-semibold">Nomor Surat</th>
                        <th class="px-4 py-4 font-semibold">Tanggal Surat</th>
                        <th class="px-4 py-4 font-semibold">NIK</th>
                        <th class="px-4 py-4 font-semibold">Nama Karyawan</th>
                        <th class="px-4 py-4 font-semibold">Mulai Berlaku</th>
                        <th class="px-4 py-4 font-semibold">Akhir Berlaku</th>
                        <th class="px-4 py-4 font-semibold">Kepala Unit</th>
                        <th class="px-4 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($warningNotices as $notice)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $warningNotices->firstItem() + $loop->index }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-800">
                                {{ $notice->nomor_surat }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $notice->tanggal_surat->format('d-m-Y') }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $notice->employee->no_induk_karyawan ?? '-' }}
                            </td>
                            <td class="px-4 py-4 font-medium">
                                {{ $notice->employee->name ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $notice->tanggal_mulai_berlaku->format('d-m-Y') }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $notice->tanggal_akhir_berlaku->format('d-m-Y') }}
                            </td>
                            <td class="px-4 py-4">
                                {{ $notice->nama_kepala_unit }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('warning-notices.show', $notice) }}"
                                       class="rounded-lg border border-emerald-200 px-3 py-1.5 text-xs font-medium text-emerald-700 transition hover:bg-emerald-50">
                                        Detail
                                    </a>

                                    <a href="{{ route('warning-notices.edit', $notice) }}"
                                       class="rounded-lg border border-amber-200 px-3 py-1.5 text-xs font-medium text-amber-700 transition hover:bg-amber-50">
                                        Edit
                                    </a>

                                    <form action="{{ route('warning-notices.destroy', $notice) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus Surat Teguran ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center">
                                <p class="font-medium text-slate-600">Belum ada data Surat Teguran</p>
                                <p class="mt-1 text-sm text-slate-400">
                                    Klik tombol Buat Surat Teguran untuk menambahkan data.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($warningNotices->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $warningNotices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection