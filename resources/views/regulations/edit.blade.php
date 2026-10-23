@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('regulations.show', $regulation) }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali ke Detail Regulasi
            </a>

            <h2 class="text-2xl font-bold text-gray-800 mt-3">
                Edit Regulasi
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Perbarui data perijinan dan regulasi PT Starfood International.
            </p>
        </div>


        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">

                <div class="font-semibold text-red-700 mb-2">
                    Terdapat kesalahan:
                </div>

                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        <form
            action="{{ route('regulations.update', $regulation) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')


            {{-- DATA PERIJINAN --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Data Perijinan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Kategori --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kategori Jenis Perijinan
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="kategori"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                Pilih Kategori
                            </option>

                            @foreach($kategoriList as $kategori)

                                <option
                                    value="{{ $kategori }}"
                                    {{ old('kategori', $regulation->kategori) == $kategori ? 'selected' : '' }}
                                >
                                    {{ $kategori }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Jenis --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Perijinan
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="jenis_perijinan"
                            value="{{ old('jenis_perijinan', $regulation->jenis_perijinan) }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>
                    {{-- Nomor Ijin --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor Ijin
                        </label>

                        <input
                            type="text"
                            name="nomor_ijin"
                            value="{{ old('nomor_ijin', $regulation->nomor_ijin) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>


                    {{-- Regulasi --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Regulasi
                        </label>

                        <input
                            type="text"
                            name="regulasi"
                            value="{{ old('regulasi', $regulation->regulasi) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>

                </div>

            </div>


            {{-- MASA BERLAKU --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Masa Berlaku
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- Mulai --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $regulation->tanggal_mulai?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>


                    {{-- Berakhir --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Berakhir
                        </label>

                        <input
                            type="date"
                            name="tanggal_berakhir"
                            value="{{ old('tanggal_berakhir', $regulation->tanggal_berakhir?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>


                    {{-- Masa --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Masa Berlaku
                        </label>

                        <input
                            type="text"
                            name="masa_berlaku"
                            value="{{ old('masa_berlaku', $regulation->masa_berlaku) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Contoh: 2 TAHUN"
                        >

                    </div>

                </div>

            </div>


            {{-- DATA TERKAIT --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Data Terkait
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Bagian --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Departement / Bagian Terkait
                        </label>

                        <input
                            type="text"
                            name="bagian_terkait"
                            value="{{ old('bagian_terkait', $regulation->bagian_terkait) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>


                    {{-- Status --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="status"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option
                                value="Terpenuhi"
                                {{ old('status', $regulation->status) == 'Terpenuhi' ? 'selected' : '' }}
                            >
                                Terpenuhi
                            </option>

                            <option
                                value="Tidak Terpenuhi"
                                {{ old('status', $regulation->status) == 'Tidak Terpenuhi' ? 'selected' : '' }}
                            >
                                Tidak Terpenuhi
                            </option>

                        </select>

                    </div>


                    {{-- Keterangan --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan
                        </label>

                        <textarea
                            name="keterangan"
                            rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('keterangan', $regulation->keterangan) }}</textarea>

                    </div>

                </div>

            </div>


            {{-- DOKUMEN --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    Dokumen Regulasi
                </h3>

                @if($regulation->dokumen)

                    <div class="mb-5 p-4 bg-gray-50 border border-gray-200 rounded-lg">

                        <div class="text-sm font-medium text-gray-700 mb-2">
                            Dokumen saat ini
                        </div>

                        <div class="flex items-center gap-3">

                            <a
                                href="{{ Storage::url($regulation->dokumen) }}"
                                target="_blank"
                                class="text-sm text-blue-600 hover:text-blue-800 underline"
                            >
                                Lihat dokumen lama
                            </a>

                            <a
                                href="{{ route('regulations.download', $regulation) }}"
                                class="text-sm text-green-600 hover:text-green-800 underline"
                            >
                                Unduh
                            </a>

                        </div>

                    </div>

                @endif


                <p class="text-sm text-gray-500 mb-4">
                    Jika ingin mengganti dokumen, pilih file baru.
                    Maksimal 5 MB.
                </p>

                <input
                    type="file"
                    name="dokumen"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="w-full text-sm border border-gray-300 rounded-lg p-2"
                >

            </div>


            {{-- BUTTON --}}
            <div class="flex items-center justify-between">

                <a
                    href="{{ route('regulations.show', $regulation) }}"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>
</div>
@endsection