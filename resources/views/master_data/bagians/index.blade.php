
@extends('layouts.app')

@section('title', 'Master Bagian')

@section('content')
<div class="space-y-6">

    <div class="soft-card p-6">
        <span class="mini-tag">Master Data HRD</span>
        <h1 class="mt-3 text-3xl font-bold text-slate-800">
            Master Bagian
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Kelola daftar bagian yang digunakan pada data karyawan.
        </p>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="soft-card p-5">
        <h2 class="mb-4 text-lg font-semibold text-slate-800">
            Tambah Bagian
        </h2>

        <form action="{{ route('master-data.bagian.store') }}"
              method="POST"
              class="flex flex-col gap-3 sm:flex-row">
            @csrf

            <input type="text"
                   name="nama"
                   value="{{ old('nama') }}"
                   placeholder="Masukkan nama bagian"
                   required
                   maxlength="255"
                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">

            <button type="submit" class="primary-btn">
                + Tambah Bagian
            </button>
        </form>
    </div>

    <div class="soft-card overflow-hidden">
        <div class="border-b border-slate-200 p-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Daftar Bagian
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Total {{ $data->total() }} data.
            </p>
        </div>

        <div class="overflow-x-auto p-5">
            <table class="min-w-full text-left text-sm text-slate-700">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                        <th class="px-3 py-3 font-semibold">No.</th>
                        <th class="px-3 py-3 font-semibold">Nama Bagian</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-3 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $item)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/80">
                            <td class="px-3 py-3">
                                {{ $data->firstItem() + $loop->index }}
                            </td>

                            <td class="px-3 py-3 font-medium text-slate-800">
                                {{ $item->nama }}
                            </td>

                            <td class="px-3 py-3">
                                @if($item->is_active)
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-medium text-slate-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-3 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <form action="{{ route('master-data.bagian.update', $item) }}"
                                          method="POST"
                                          class="flex flex-wrap gap-2">
                                        @csrf
                                        @method('PUT')

                                        <input type="text"
                                               name="nama"
                                               value="{{ $item->nama }}"
                                               required
                                               maxlength="255"
                                               aria-label="Nama bagian"
                                               class="w-40 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs">

                                        <button type="submit"
                                                class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-200">
                                            Simpan
                                        </button>
                                    </form>

                                    <form action="{{ route('master-data.bagian.toggle', $item) }}"
                                          method="POST"
                                          onsubmit="return confirm('Ubah status bagian ini?')">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">
                                            {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-10 text-center text-slate-500">
                                Belum ada data bagian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $data->links() }}
        </div>
    </div>

</div>
@endsection
