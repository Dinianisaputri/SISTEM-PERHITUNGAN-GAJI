@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Data Regulasi
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Kelola data perijinan dan regulasi PT Starfood International.
                </p>
            </div>

            <a href="{{ route('regulations.create') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                + Tambah Regulasi
            </a>
        </div>


        {{-- Alert sukses --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="text-sm text-green-700">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Alert error --}}
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="text-sm text-red-700">
                    {{ session('error') }}
                </div>
            </div>
        @endif


        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">

            <form method="GET" action="{{ route('regulations.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Pencarian --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Cari Regulasi
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari jenis, nomor surat, nomor ijin..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>


                    {{-- Kategori --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kategori
                        </label>

                        <select
                            name="kategori"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">
                                Semua Kategori
                            </option>

                            @foreach($kategoriList as $kategori)
                                <option
                                    value="{{ $kategori }}"
                                    {{ request('kategori') == $kategori ? 'selected' : '' }}
                                >
                                    {{ $kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">
                                Semua Status
                            </option>

                            <option
                                value="Terpenuhi"
                                {{ request('status') == 'Terpenuhi' ? 'selected' : '' }}
                            >
                                Terpenuhi
                            </option>

                            <option
                                value="Tidak Terpenuhi"
                                {{ request('status') == 'Tidak Terpenuhi' ? 'selected' : '' }}
                            >
                                Tidak Terpenuhi
                            </option>
                        </select>
                    </div>

                </div>


                <div class="flex items-center gap-2 mt-4">

                    <button
                        type="submit"
                        class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-900 transition"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('regulations.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition"
                    >
                        Reset
                    </a>

                </div>

            </form>
        </div>


        {{-- Tabel --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Daftar Regulasi
                </h3>

                <p class="text-xs text-gray-500 mt-1">
                    Total {{ $regulations->total() }} data
                </p>
            </div>


  <div class="p-5">
    <div class="w-full overflow-x-auto rounded-xl border border-slate-200">
        <table class="min-w-[1100px] w-full text-left text-sm text-slate-700">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                    <th class="whitespace-nowrap px-3 py-3 font-semibold">No</th>
                    <th class="whitespace-nowrap px-3 py-3 font-semibold">Jenis Perijinan</th>
                    <th class="whitespace-nowrap px-3 py-3 font-semibold">Nomor</th>
                    <th class="whitespace-nowrap px-3 py-3 font-semibold">Kategori</th>
                    <th class="whitespace-nowrap px-3 py-3 font-semibold">Berakhir</th>
                    <th class="whitespace-nowrap px-3 py-3 font-semibold">Status</th>
                    <th class="whitespace-nowrap px-3 py-3 font-semibold">Dokumen</th>
                    <th class="whitespace-nowrap px-3 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($regulations as $index => $regulation)

                            @php
                                $hariTersisa = null;
                                $statusMasaBerlaku = null;

                                if ($regulation->tanggal_berakhir) {
                                    $hariTersisa = now()->startOfDay()
                                        ->diffInDays(
                                            $regulation->tanggal_berakhir->startOfDay(),
                                            false
                                        );

                                    if ($hariTersisa < 0) {
                                        $statusMasaBerlaku = 'expired';
                                    } elseif ($hariTersisa <= 30) {
                                        $statusMasaBerlaku = 'warning';
                                    } elseif ($hariTersisa <= 90) {
                                        $statusMasaBerlaku = 'soon';
                                    } else {
                                        $statusMasaBerlaku = 'safe';
                                    }
                                }
                            @endphp


                            <tr class="hover:bg-gray-50">

                                {{-- No --}}
                                <td class="px-4 py-4 text-gray-500">
                                    {{ $regulations->firstItem() + $index }}
                                </td>


                                {{-- Jenis --}}
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-800">
                                        {{ $regulation->jenis_perijinan }}
                                    </div>

                                    @if($regulation->regulasi)
                                        <div class="text-xs text-gray-500 mt-1 max-w-xs">
                                            {{ Str::limit($regulation->regulasi, 60) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Nomor Ijin --}}
                                <td class="px-4 py-4 text-gray-700">
                                    {{ $regulation->nomor_ijin ?: '-' }}
                                </td>


                                {{-- Kategori --}}
                                <td class="px-4 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs">
                                        {{ $regulation->kategori }}
                                    </span>
                                </td>


                                {{-- Tanggal berakhir --}}
                                <td class="px-4 py-4">

                                    @if($regulation->tanggal_berakhir)

                                        <div class="text-gray-700">
                                            {{ $regulation->tanggal_berakhir->format('d-m-Y') }}
                                        </div>

                                        @if($statusMasaBerlaku === 'expired')

                                            <div class="text-xs text-red-600 mt-1 font-medium">
                                                Expired {{ abs($hariTersisa) }} hari
                                            </div>

                                        @elseif($statusMasaBerlaku === 'warning')

                                            <div class="text-xs text-orange-600 mt-1 font-medium">
                                                {{ $hariTersisa }} hari lagi
                                            </div>

                                        @elseif($statusMasaBerlaku === 'soon')

                                            <div class="text-xs text-yellow-600 mt-1">
                                                {{ $hariTersisa }} hari lagi
                                            </div>

                                        @else

                                            <div class="text-xs text-green-600 mt-1">
                                                {{ $hariTersisa }} hari lagi
                                            </div>

                                        @endif

                                    @else

                                        <span class="text-gray-400">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="px-4 py-4">

                                    @if($regulation->status === 'Terpenuhi')

                                        <span class="inline-flex px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium">
                                            Terpenuhi
                                        </span>

                                    @else

                                        <span class="inline-flex px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium">
                                            Tidak Terpenuhi
                                        </span>

                                    @endif

                                </td>


                                {{-- Dokumen --}}
                                <td class="px-4 py-4 text-center">

                                    @if($regulation->dokumen)

                                        <div class="flex items-center justify-center gap-2">

                                            <a
                                                href="{{ Storage::url($regulation->dokumen) }}"
                                                target="_blank"
                                                class="text-blue-600 hover:text-blue-800 text-xs font-medium"
                                            >
                                                Lihat
                                            </a>

                                            <a
                                                href="{{ route('regulations.download', $regulation) }}"
                                                class="text-green-600 hover:text-green-800 text-xs font-medium"
                                            >
                                                Unduh
                                            </a>

                                        </div>

                                    @else

                                        <span class="text-gray-400 text-xs">
                                            Tidak ada
                                        </span>

                                    @endif

                                </td>


                                {{-- Aksi --}}
                                <td class="px-4 py-4">

                                    <div class="flex items-center justify-center gap-2">

                                        <a
                                            href="{{ route('regulations.show', $regulation) }}"
                                            class="px-2.5 py-1.5 bg-gray-100 text-gray-700 rounded-md text-xs hover:bg-gray-200"
                                        >
                                            Lihat
                                        </a>

                                        <a
                                            href="{{ route('regulations.edit', $regulation) }}"
                                            class="px-2.5 py-1.5 bg-blue-50 text-blue-700 rounded-md text-xs hover:bg-blue-100"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('regulations.destroy', $regulation) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data regulasi ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="px-2.5 py-1.5 bg-red-50 text-red-700 rounded-md text-xs hover:bg-red-100"
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
                                    colspan="9"
                                    class="px-5 py-12 text-center"
                                >
                                    <div class="text-gray-400 text-sm">
                                        Belum ada data regulasi.
                                    </div>

                                    <a
                                        href="{{ route('regulations.create') }}"
                                        class="inline-block mt-3 text-sm text-blue-600 hover:text-blue-800"
                                    >
                                        + Tambah regulasi pertama
                                    </a>
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($regulations->hasPages())

                <div class="px-5 py-4 border-t border-gray-200">
                    {{ $regulations->links() }}
                </div>

            @endif

        </div>

    </div>
</div>
@endsection