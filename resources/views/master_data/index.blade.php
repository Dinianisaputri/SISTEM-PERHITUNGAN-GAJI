
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ $judul }}
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Kelola pilihan yang digunakan pada formulir karyawan
            dan regulasi.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 rounded-xl border bg-white p-5 shadow-sm">
        <h2 class="mb-4 font-semibold">Tambah Data</h2>

        <form method="POST"
              action="{{ route('master-data.store', $jenis) }}"
              class="flex flex-col gap-3 sm:flex-row">
            @csrf

            <input
                type="text"
                name="nama"
                value="{{ old('nama') }}"
                placeholder="Masukkan nama..."
                required
                maxlength="255"
                class="w-full rounded-lg border-gray-300 focus:border-teal-600 focus:ring-teal-600"
            >

            <button
                type="submit"
                class="rounded-lg bg-teal-700 px-5 py-2 font-medium text-white hover:bg-teal-800"
            >
                Tambah
            </button>
        </form>
    </div>

    <div class="overflow-x-auto rounded-xl border bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">No.</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($data as $item)
                    <tr>
                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3">
                            <form
                                id="edit-master-{{ $item->id }}"
                                method="POST"
                                action="{{ route('master-data.update', [
                                    'jenis' => $jenis,
                                    'id' => $item->id
                                ]) }}"
                                class="flex min-w-64 gap-2"
                            >
                                @csrf
                                @method('PUT')

                                <input
                                    type="text"
                                    name="nama"
                                    value="{{ $item->nama }}"
                                    required
                                    maxlength="255"
                                    class="w-full rounded-lg border-gray-300"
                                >
                            </form>
                        </td>

                        <td class="px-4 py-3">
                            @if ($item->is_active)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-green-700">
                                    Aktif
                                </span>
                            @else
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600">
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="submit"
                                    form="edit-master-{{ $item->id }}"
                                    class="rounded-lg bg-blue-600 px-3 py-2 text-white hover:bg-blue-700"
                                >
                                    Simpan
                                </button>

                                <form
                                    method="POST"
                                    action="{{ route('master-data.toggle', [
                                        'jenis' => $jenis,
                                        'id' => $item->id
                                    ]) }}"
                                    onsubmit="return confirm('Yakin ingin mengubah status master ini?')"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="rounded-lg px-3 py-2 text-white {{ $item->is_active ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' }}"
                                    >
                                        {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            Belum ada data master.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
