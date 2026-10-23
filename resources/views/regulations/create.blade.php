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

            <h2 class="text-2xl font-bold text-gray-800 mt-3">
                Tambah Regulasi
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Tambahkan data perijinan dan regulasi PT Starfood International.
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
            action="{{ route('regulations.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf


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
                                    {{ old('kategori') == $kategori ? 'selected' : '' }}
                                >
                                    {{ $kategori }}
                                </option>
                            @endforeach
                        </select>

                    </div>


                    {{-- Jenis Perijinan --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Perijinan
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="jenis_perijinan"
                            value="{{ old('jenis_perijinan') }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Contoh: NKV"
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
                            value="{{ old('nomor_ijin') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan nomor ijin"
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
                            value="{{ old('regulasi') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan regulasi atau dasar hukum"
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

                    {{-- Tanggal Mulai --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            value="{{ old('tanggal_mulai') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>


                    {{-- Tanggal Berakhir --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Berakhir
                        </label>

                        <input
                            type="date"
                            name="tanggal_berakhir"
                            value="{{ old('tanggal_berakhir') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>


                    {{-- Masa Berlaku --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Masa Berlaku
                        </label>

                        <input
                            type="text"
                            name="masa_berlaku"
                            value="{{ old('masa_berlaku') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Contoh: 2 TAHUN"
                        >

                    </div>

                </div>

                <p class="text-xs text-gray-500 mt-3">
                    Masa berlaku dapat diisi dengan keterangan seperti
                    1 TAHUN, 2 TAHUN, 5 TAHUN, atau sesuai ketentuan dokumen.
                </p>

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
                            value="{{ old('bagian_terkait') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Contoh: QA, HRD, Teknik"
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
                                {{ old('status', 'Terpenuhi') == 'Terpenuhi' ? 'selected' : '' }}
                            >
                                Terpenuhi
                            </option>

                            <option
                                value="Tidak Terpenuhi"
                                {{ old('status') == 'Tidak Terpenuhi' ? 'selected' : '' }}
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
                            placeholder="Masukkan keterangan tambahan"
                        >{{ old('keterangan') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- DOKUMEN --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    Dokumen Regulasi
                </h3>

                <p class="text-sm text-gray-500 mb-5">
                    Upload dokumen dalam format PDF, JPG, JPEG, atau PNG.
                    Maksimal 5 MB.
                </p>

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Dokumen
                    </label>

                    <input
                        type="file"
                        name="dokumen"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full text-sm border border-gray-300 rounded-lg p-2"
                    >

                </div>

            </div>


            {{-- BUTTON --}}
            <div class="flex items-center justify-end gap-3">

                <a
                    href="{{ route('regulations.index') }}"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    Simpan Regulasi
                </button>

            </div>

        </form>

    </div>
</div>
@endsection