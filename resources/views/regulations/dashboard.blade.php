@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Dashboard Regulasi
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Ringkasan data regulasi dan masa berlaku perijinan.
            </p>
        </div>


        {{-- Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

            {{-- Total --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="text-sm text-gray-500">
                    Total Regulasi
                </div>

                <div class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $totalRegulasi }}
                </div>
            </div>


            {{-- Terpenuhi --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="text-sm text-gray-500">
                    Terpenuhi
                </div>

                <div class="text-3xl font-bold text-green-600 mt-2">
                    {{ $terpenuhi }}
                </div>
            </div>


            {{-- Tidak Terpenuhi --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="text-sm text-gray-500">
                    Tidak Terpenuhi
                </div>

                <div class="text-3xl font-bold text-red-600 mt-2">
                    {{ $tidakTerpenuhi }}
                </div>
            </div>


            {{-- Expired --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="text-sm text-gray-500">
                    Sudah Expired
                </div>

                <div class="text-3xl font-bold text-red-600 mt-2">
                    {{ $expired }}
                </div>
            </div>

        </div>


        {{-- Status Masa Berlaku --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-5">
                Status Masa Berlaku
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div class="border border-orange-200 bg-orange-50 rounded-lg p-4">
                    <div class="text-sm text-orange-700">
                        Berakhir ≤ 30 Hari
                    </div>

                    <div class="text-2xl font-bold text-orange-700 mt-1">
                        {{ $segeraBerakhir }}
                    </div>
                </div>


                <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4">
                    <div class="text-sm text-yellow-700">
                        Berakhir 31–90 Hari
                    </div>

                    <div class="text-2xl font-bold text-yellow-700 mt-1">
                        {{ $akanBerakhir }}
                    </div>
                </div>


                <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                    <div class="text-sm text-green-700">
                        Lebih dari 90 Hari
                    </div>

                    <div class="text-2xl font-bold text-green-700 mt-1">
                        {{ $masihAktif }}
                    </div>
                </div>

            </div>

        </div>


        {{-- Akan Berakhir --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Regulasi yang Akan Berakhir
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Regulasi dengan masa berlaku maksimal 90 hari.
                        </p>
                    </div>

                    <a
                        href="{{ route('regulations.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800"
                    >
                        Lihat Semua
                    </a>

                </div>
            </div>


            @if($regulasiAkanBerakhir->count())

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-50">
                            <tr>

                                <th class="px-6 py-3 text-left font-medium text-gray-600">
                                    Jenis Perijinan
                                </th>

                                <th class="px-6 py-3 text-left font-medium text-gray-600">
                                    Nomor Ijin
                                </th>

                                <th class="px-6 py-3 text-left font-medium text-gray-600">
                                    Bagian
                                </th>

                                <th class="px-6 py-3 text-left font-medium text-gray-600">
                                    Tanggal Berakhir
                                </th>

                                <th class="px-6 py-3 text-left font-medium text-gray-600">
                                    Sisa
                                </th>

                                <th class="px-6 py-3 text-left font-medium text-gray-600">
                                    Aksi
                                </th>

                            </tr>
                        </thead>


                        <tbody class="divide-y divide-gray-200">

                            @foreach($regulasiAkanBerakhir as $regulation)

                                @php
                                    $hariTersisa = now()->startOfDay()->diffInDays(
                                        $regulation->tanggal_berakhir->startOfDay(),
                                        false
                                    );
                                @endphp

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800">
                                            {{ $regulation->jenis_perijinan }}
                                        </div>

                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $regulation->kategori }}
                                        </div>
                                    </td>


                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $regulation->nomor_ijin ?: '-' }}
                                    </td>


                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $regulation->bagian_terkait ?: '-' }}
                                    </td>


                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $regulation->tanggal_berakhir->format('d-m-Y') }}
                                    </td>


                                    <td class="px-6 py-4">

                                        @if($hariTersisa <= 30)

                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-medium">
                                                {{ $hariTersisa }} hari
                                            </span>

                                        @else

                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-medium">
                                                {{ $hariTersisa }} hari
                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-6 py-4">

                                        <a
                                            href="{{ route('regulations.show', $regulation) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium"
                                        >
                                            Lihat
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-8 text-center text-sm text-gray-500">
                    Tidak ada regulasi yang akan berakhir dalam 90 hari.
                </div>

            @endif

        </div>

    </div>
</div>
@endsection