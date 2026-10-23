@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('regulations.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali ke Data Regulasi
            </a>

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mt-3">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Detail Regulasi
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Informasi lengkap data perijinan dan regulasi.
                    </p>
                </div>

                <a
                    href="{{ route('regulations.edit', $regulation) }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition"
                >
                    Edit Regulasi
                </a>
            </div>
        </div>


        {{-- Informasi Utama --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-5">
                Data Perijinan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">

                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        Kategori Jenis Perijinan
                    </div>

                    <div class="text-sm font-medium text-gray-800">
                        {{ $regulation->kategori ?: '-' }}
                    </div>
                </div>


                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        Jenis Perijinan
                    </div>

                    <div class="text-sm font-medium text-gray-800">
                        {{ $regulation->jenis_perijinan ?: '-' }}
                    </div>
                </div>


                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        Nomor Surat
                    </div>

                    <div class="text-sm text-gray-800">
                        {{ $regulation->nomor_surat ?: '-' }}
                    </div>
                </div>


                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        Nomor Ijin
                    </div>

                    <div class="text-sm text-gray-800">
                        {{ $regulation->nomor_ijin ?: '-' }}
                    </div>
                </div>


                <div class="md:col-span-2">
                    <div class="text-xs text-gray-500 mb-1">
                        Regulasi
                    </div>

                    <div class="text-sm text-gray-800 whitespace-pre-line">
                        {{ $regulation->regulasi ?: '-' }}
                    </div>
                </div>

            </div>

        </div>


        {{-- Masa Berlaku --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-5">
                Masa Berlaku
            </h3>

            @php
                $hariTersisa = null;

                if ($regulation->tanggal_berakhir) {
                    $hariTersisa = now()->startOfDay()->diffInDays(
                        $regulation->tanggal_berakhir->startOfDay(),
                        false
                    );
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        Tanggal Mulai
                    </div>

                    <div class="text-sm text-gray-800">
                        {{ $regulation->tanggal_mulai?->format('d-m-Y') ?? '-' }}
                    </div>
                </div>


                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        Tanggal Berakhir
                    </div>

                    <div class="text-sm font-medium text-gray-800">
                        {{ $regulation->tanggal_berakhir?->format('d-m-Y') ?? '-' }}
                    </div>
                </div>


                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        Masa Berlaku
                    </div>

                    <div class="text-sm text-gray-800">
                        {{ $regulation->masa_berlaku ?: '-' }}
                    </div>
                </div>


                @if($hariTersisa !== null)
                    <div class="md:col-span-3">

                        @if($hariTersisa < 0)

                            <div class="p-4 rounded-lg bg-red-50 border border-red-200">
                                <div class="text-sm font-semibold text-red-700">
                                    Masa berlaku sudah expired
                                </div>

                                <div class="text-xs text-red-600 mt-1">
                                    Sudah melewati masa berlaku
                                    {{ abs($hariTersisa) }} hari.
                                </div>
                            </div>

                        @elseif($hariTersisa <= 30)

                            <div class="p-4 rounded-lg bg-orange-50 border border-orange-200">
                                <div class="text-sm font-semibold text-orange-700">
                                    Masa berlaku segera berakhir
                                </div>

                                <div class="text-xs text-orange-600 mt-1">
                                    Tersisa {{ $hariTersisa }} hari lagi.
                                </div>
                            </div>

                        @elseif($hariTersisa <= 90)

                            <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                                <div class="text-sm font-semibold text-yellow-700">
                                    Masa berlaku akan berakhir
                                </div>

                                <div class="text-xs text-yellow-600 mt-1">
                                    Tersisa {{ $hariTersisa }} hari lagi.
                                </div>
                            </div>

                        @else

                            <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                                <div class="text-sm font-semibold text-green-700">
                                    Masa berlaku masih aktif
                                </div>

                                <div class="text-xs text-green-600 mt-1">
                                    Tersisa {{ $hariTersisa }} hari lagi.
                                </div>
                            </div>

                        @endif

                    </div>
                @endif

            </div>

        </div>


        {{-- Data Terkait --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-5">
                Data Terkait
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">

                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        Departement / Bagian Terkait
                    </div>

                    <div class="text-sm text-gray-800">
                        {{ $regulation->bagian_terkait ?: '-' }}
                    </div>
                </div>


                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        Status
                    </div>

                    @if($regulation->status === 'Terpenuhi')
                        <span class="inline-flex px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium">
                            Terpenuhi
                        </span>
                    @else
                        <span class="inline-flex px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium">
                            Tidak Terpenuhi
                        </span>
                    @endif
                </div>


                <div class="md:col-span-2">

                    <div class="text-xs text-gray-500 mb-1">
                        Keterangan
                    </div>

                    <div class="text-sm text-gray-800 whitespace-pre-line">
                        {{ $regulation->keterangan ?: '-' }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Dokumen --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Dokumen Regulasi
            </h3>

            @if($regulation->dokumen)

                <p class="text-sm text-gray-500 mb-4">
                    Dokumen tersedia dan dapat dilihat atau diunduh.
                </p>

                <div class="flex items-center gap-3">

                    <a
                        href="{{ Storage::url($regulation->dokumen) }}"
                        target="_blank"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition"
                    >
                        Lihat Dokumen
                    </a>

                    <a
                        href="{{ route('regulations.download', $regulation) }}"
                        class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition"
                    >
                        Unduh Dokumen
                    </a>

                </div>

            @else

                <div class="text-sm text-gray-400">
                    Belum ada dokumen yang diunggah.
                </div>

            @endif

        </div>

    </div>
</div>
@endsection