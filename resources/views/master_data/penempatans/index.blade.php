
@extends('layouts.app')

@section('title', 'Master Penempatan')

@section('content')
<div class="space-y-6">
    <div class="soft-card p-6">
        <span class="mini-tag">Master Data HRD</span>
        <h1 class="mt-3 text-3xl font-bold text-slate-800">Master Penempatan</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola unit atau lokasi penempatan karyawan.</p>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="soft-card p-5">
        <h2 class="mb-4 text-lg font-semibold text-slate-800">Tambah Penempatan</h2>
        <form action="{{ route('master-data.penempatan.store') }}" method="POST" class="flex flex-col gap-3 sm:flex-row">
            @csrf
            <input name="nama" value="{{ old('nama') }}" required maxlength="255" placeholder="Nama penempatan"
                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm">
            <button class="primary-btn" type="submit">+ Tambah Penempatan</button>
        </form>
    </div>

    <div class="soft-card overflow-hidden">
        <div class="border-b border-slate-200 p-5">
            <h2 class="text-lg font-semibold text-slate-800">Daftar Penempatan</h2>
            <p class="mt-1 text-sm text-slate-500">Total {{ $data->total() }} data.</p>
        </div>
        <div class="overflow-x-auto p-5">
            <table class="min-w-full text-left text-sm text-slate-700">
                <thead><tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                    <th class="px-3 py-3">No.</th><th class="px-3 py-3">Nama Penempatan</th>
                    <th class="px-3 py-3">Aksi</th>
                </tr></thead>
                <tbody>
                @forelse($data as $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50/80">
                        <td class="px-3 py-3">{{ $data->firstItem() + $loop->index }}</td>
                        <td class="px-3 py-3 font-medium text-slate-800">{{ $item->nama }}</td>
                        <td class="px-3 py-3">
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('master-data.penempatan.update', $item) }}" method="POST" class="flex flex-wrap gap-2">
                                    @csrf @method('PUT')
                                    <input name="nama" value="{{ $item->nama }}" required maxlength="255" aria-label="Nama penempatan"
                                           class="w-40 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs">
                                    <button class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-xs text-amber-700" type="submit">Simpan</button>
                                </form>
                                <form action="{{ route('master-data.penempatan.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus penempatan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-lg bg-rose-100 px-2.5 py-1.5 text-xs text-rose-700" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-3 py-10 text-center text-slate-500">Belum ada data penempatan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-5 py-4">{{ $data->links() }}</div>
    </div>
</div>
@endsection
