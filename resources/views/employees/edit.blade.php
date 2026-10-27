@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="mb-6">
                <a href="{{ route('employees.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700">
                    ← Kembali ke Data Karyawan
                </a>

                <h2 class="text-2xl font-bold text-gray-800 mt-3">
                    Edit Karyawan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Perbarui data karyawan PT Starfood International.
                </p>
            </div>

            {{-- ERROR --}}
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

            <form action="{{ route('employees.update', $employee) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- ===================================================== --}}
                {{-- DATA IDENTITAS --}}
                {{-- ===================================================== --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-5">
                        Data Identitas
                    </h3>

                    {{-- PAS FOTO --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pas Foto
                        </label>

                        <div class="flex items-start gap-4">

                            {{-- PREVIEW FOTO --}}
                            <div class="w-[120px] h-[160px] overflow-hidden rounded-lg border border-gray-200 bg-gray-50 shadow-sm flex-shrink-0">

                                @if($employee->pas_foto)

                                    <img
                                        src="{{ Storage::url($employee->pas_foto) }}"
                                        alt="Pas Foto {{ $employee->name }}"
                                        class="w-full h-full object-cover"
                                    >

                                @else

                                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-400 text-center">
                                        No Photo
                                    </div>

                                @endif

                            </div>

                            {{-- KETERANGAN --}}
                            <div class="text-sm text-gray-500 pt-1">
                                <p class="font-medium text-gray-700">
                                    Pas Foto Karyawan
                                </p>

                                <p class="mt-1">
                                    Upload foto baru jika ingin mengganti foto yang sudah ada.
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    JPG, JPEG, PNG. Maksimal 2 MB.
                                </p>
                            </div>

                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- NAMA --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Karyawan <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $employee->name) }}"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan nama lengkap"
                            >
                        </div>

                        {{-- NO INDUK --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                No. Induk Karyawan
                            </label>

                            <input
                                type="text"
                                name="no_induk_karyawan"
                                value="{{ old('no_induk_karyawan', $employee->no_induk_karyawan) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Nomor induk karyawan"
                            >
                        </div>
{{-- Nomor Rekening --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Nomor Rekening
    </label>

    <input
        type="text"
        name="no_rekening"
        value="{{ old('no_rekening', $employee->no_rekening) }}"
        maxlength="50"
        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
        placeholder="Masukkan nomor rekening"
    >

    @error('no_rekening')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
                        {{-- PIN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                PIN Fingerprint
                            </label>

                            <input
                                type="text"
                                name="fingerprint_pin"
                                value="{{ old('fingerprint_pin', $employee->fingerprint_pin) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="PIN fingerprint"
                            >
                        </div>

                        {{-- JABATAN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jabatan
                            </label>

                            <select
                                name="jabatan"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Pilih Jabatan</option>

                                @foreach($jabatanList as $jabatan)
                                    <option
                                        value="{{ $jabatan }}"
                                        {{ old('jabatan', $employee->jabatan) == $jabatan ? 'selected' : '' }}
                                    >
                                        {{ $jabatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BAGIAN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Bagian <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="bagian"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Pilih Bagian</option>

                                @foreach($bagianList as $bagian)
                                    <option
                                        value="{{ $bagian }}"
                                        {{ old('bagian', $employee->bagian) == $bagian ? 'selected' : '' }}
                                    >
                                        {{ $bagian }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                                                {{-- Unit Penempatan --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Unit Penempatan <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="penempatan"
                                required
                                class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Pilih Unit Penempatan</option>

                                @foreach ($penempatanList as $penempatan)
                                    <option
                                        value="{{ $penempatan }}"
                                        {{ old('penempatan', $employee->penempatan) == $penempatan ? 'selected' : '' }}
                                    >
                                        {{ $penempatan }}
                                    </option>
                                @endforeach
                            </select>

                            @error('penempatan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
               {{-- JENIS KARYAWAN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Karyawan
                            </label>

                            <select
                                name="jenis_karyawan"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Pilih Jenis</option>

                                <option value="Borongan"
                                    {{ old('jenis_karyawan', $employee->jenis_karyawan) == 'Borongan' ? 'selected' : '' }}>
                                    Borongan
                                </option>

                                <option value="Bulanan"
                                    {{ old('jenis_karyawan', $employee->jenis_karyawan) == 'Bulanan' ? 'selected' : '' }}>
                                    Bulanan
                                </option>

                                <option value="Harian"
                                    {{ old('jenis_karyawan', $employee->jenis_karyawan) == 'Harian' ? 'selected' : '' }}>
                                    Harian
                                </option>
                            </select>
                        </div>

                        {{-- PENDIDIKAN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Pendidikan
                            </label>

                            <select
                                name="pendidikan"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Pilih Pendidikan</option>

                                @foreach(['SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $item)
                                    <option
                                        value="{{ $item }}"
                                        {{ old('pendidikan', $employee->pendidikan) == $item ? 'selected' : '' }}
                                    >
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- STATUS PERNIKAHAN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status Pernikahan
                            </label>

                            <select
                                name="status_pernikahan"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Pilih Status</option>

                                <option value="Belum Menikah"
                                    {{ old('status_pernikahan', $employee->status_pernikahan) == 'Belum Menikah' ? 'selected' : '' }}>
                                    Belum Menikah
                                </option>

                                <option value="Sudah Menikah"
                                    {{ old('status_pernikahan', $employee->status_pernikahan) == 'Sudah Menikah' ? 'selected' : '' }}>
                                    Sudah Menikah
                                </option>
                            </select>
                        </div>

                        {{-- JENIS KELAMIN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Kelamin
                            </label>

                            <select
                                name="jenis_kelamin"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Pilih Jenis Kelamin</option>

                                <option value="Laki-laki"
                                    {{ old('jenis_kelamin', $employee->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>

                                <option value="Perempuan"
                                    {{ old('jenis_kelamin', $employee->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                        </div>

                        {{-- TEMPAT LAHIR --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tempat Lahir
                            </label>

                            <input
                                type="text"
                                name="tempat_lahir"
                                value="{{ old('tempat_lahir', $employee->tempat_lahir) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Contoh: Lamongan"
                            >
                        </div>

                        {{-- TANGGAL LAHIR --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Lahir
                            </label>

                            <input
                                type="date"
                                name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $employee->tanggal_lahir?->format('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        {{-- KTP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                No. KTP
                            </label>

                            <input
                                type="text"
                                name="no_ktp"
                                value="{{ old('no_ktp', $employee->no_ktp) }}"
                                maxlength="16"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="16 digit nomor KTP"
                            >
                        </div>

                        {{-- ALAMAT DOMISILI --}}
<div class="md:col-span-2">

    <label class="block text-sm font-medium text-gray-700 mb-3">
        Alamat Domisili
    </label>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- PROVINSI --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">
                Provinsi
            </label>

            <select
                name="provinsi_id"
                id="provinsi_id"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Pilih Provinsi</option>

                @foreach ($provinsiList as $provinsi)
                    <option
                        value="{{ $provinsi->id }}"
                        {{ old('provinsi_id', $employee->provinsi_id) == $provinsi->id ? 'selected' : '' }}
                    >
                        {{ $provinsi->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- KABUPATEN --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">
                Kabupaten / Kota
            </label>

            <select
                name="kabupaten_id"
                id="kabupaten_id"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Pilih Kabupaten / Kota</option>

                @foreach ($kabupatenList as $kabupaten)
                    <option
                        value="{{ $kabupaten->id }}"
                        {{ old('kabupaten_id', $employee->kabupaten_id) == $kabupaten->id ? 'selected' : '' }}
                    >
                        {{ $kabupaten->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- KECAMATAN --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">
                Kecamatan
            </label>

            <select
                name="kecamatan_id"
                id="kecamatan_id"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Pilih Kecamatan</option>

                @foreach ($kecamatanList as $kecamatan)
                    <option
                        value="{{ $kecamatan->id }}"
                        {{ old('kecamatan_id', $employee->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}
                    >
                        {{ $kecamatan->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- DESA --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">
                Desa / Kelurahan
            </label>

            <select
                name="desa_id"
                id="desa_id"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Pilih Desa / Kelurahan</option>

                @foreach ($desaList as $desa)
                    <option
                        value="{{ $desa->id }}"
                        {{ old('desa_id', $employee->desa_id) == $desa->id ? 'selected' : '' }}
                    >
                        {{ $desa->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- RT --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">
                RT
            </label>

            <input
                type="text"
                name="rt"
                value="{{ old('rt', $employee->rt) }}"
                maxlength="10"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Contoh: 001"
            >
        </div>

        {{-- RW --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">
                RW
            </label>

            <input
                type="text"
                name="rw"
                value="{{ old('rw', $employee->rw) }}"
                maxlength="10"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Contoh: 002"
            >
        </div>

        {{-- DETAIL ALAMAT --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">
                Detail Alamat
            </label>

            <textarea
                name="detail_alamat"
                rows="3"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Nama jalan, dusun, nomor rumah, patokan, dan detail lainnya"
            >{{ old('detail_alamat', $employee->detail_alamat) }}</textarea>
        </div>

    </div>
</div>

                {{-- ===================================================== --}}
                {{-- DATA PEKERJAAN --}}
                {{-- ===================================================== --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-5">
                        Data Pekerjaan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- MULAI KERJA --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Mulai Kerja
                            </label>

                            <input
                                type="date"
                                name="mulai_kerja"
                                value="{{ old('mulai_kerja', $employee->mulai_kerja?->format('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        {{-- TANGGAL KONTRAK --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Kontrak
                            </label>

                            <input
                                type="date"
                                name="tanggal_kontrak"
                                value="{{ old('tanggal_kontrak', $employee->tanggal_kontrak?->format('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        {{-- SELESAI KONTRAK --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Selesai Kontrak
                            </label>

                            <input
                                type="date"
                                name="selesai_kontrak"
                                value="{{ old('selesai_kontrak', $employee->selesai_kontrak?->format('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        {{-- KONTRAK KE --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kontrak Ke-
                            </label>

                            <input
                                type="number"
                                name="kontrak_ke"
                                value="{{ old('kontrak_ke', $employee->kontrak_ke) }}"
                                min="1"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Contoh: 1"
                            >
                        </div>

                        {{-- JUMLAH SP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah SP
                            </label>

                            <input
                                type="number"
                                name="jumlah_sp"
                                value="{{ old('jumlah_sp', $employee->jumlah_sp ?? 0) }}"
                                min="0"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="0"
                            >
                        </div>

                        {{-- STATUS --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status Karyawan
                            </label>

                            <select
                                name="is_active"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="1"
                                    {{ old('is_active', $employee->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="0"
                                    {{ old('is_active', $employee->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>
                        </div>

                    </div>
                </div>

                {{-- ===================================================== --}}
                {{-- DOKUMEN --}}
                {{-- ===================================================== --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        Dokumen Karyawan
                    </h3>

                    <p class="text-sm text-gray-500 mb-5">
                        Upload dokumen dalam format JPG, JPEG, PNG, atau PDF.
                        Maksimal 2 MB per file.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-5">

                        @foreach([
                            'pas_foto' => 'Pas Foto',
                            'foto_kk' => 'Foto KK',
                            'foto_ktp' => 'Foto KTP',
                            'foto_biodata' => 'Foto Biodata',
                            'surat_kontrak' => 'Surat Kontrak'
                        ] as $field => $label)

                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $label }}
                                </label>

                                <input
                                    type="file"
                                    name="{{ $field }}"
                                    accept="{{ $field === 'pas_foto'
                                        ? '.jpg,.jpeg,.png'
                                        : '.jpg,.jpeg,.png,.pdf' }}"
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2"
                                >

                                @if($employee->$field)

                                    <div class="mt-2 text-xs text-blue-600">
                                        <a
                                            href="{{ Storage::url($employee->$field) }}"
                                            target="_blank"
                                            class="underline"
                                        >
                                            Lihat file lama
                                        </a>
                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>
                </div>

                {{-- ===================================================== --}}
                {{-- BUTTON --}}
                {{-- ===================================================== --}}
                <div class="flex items-center justify-end gap-3">

                    <a
                        href="{{ route('employees.index') }}"
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
    <script>
document.addEventListener('DOMContentLoaded', function () {

    const provinsi = document.getElementById('provinsi_id');
    const kabupaten = document.getElementById('kabupaten_id');
    const kecamatan = document.getElementById('kecamatan_id');
    const desa = document.getElementById('desa_id');

    const regionUrl = "{{ route('employees.regions') }}";

    function resetSelect(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
        select.classList.add('bg-gray-50');
    }

    function enableSelect(select) {
        select.disabled = false;
        select.classList.remove('bg-gray-50');
    }

    async function loadRegions(parentId, level, target, placeholder, selectedId = null) {

        resetSelect(target, 'Memuat...');

        if (!parentId) {
            resetSelect(target, placeholder);
            return;
        }

        try {
            const response = await fetch(
                `${regionUrl}?level=${level}&parent_id=${parentId}`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error('Gagal mengambil data wilayah.');
            }

            const regions = await response.json();

            resetSelect(target, placeholder);

            regions.forEach(region => {

                const option = document.createElement('option');

                option.value = region.id;
                option.textContent = region.nama;

                if (selectedId && String(selectedId) === String(region.id)) {
                    option.selected = true;
                }

                target.appendChild(option);
            });

            if (regions.length > 0) {
                enableSelect(target);
            }

        } catch (error) {
            console.error(error);
            resetSelect(target, placeholder);
        }
    }

    provinsi.addEventListener('change', function () {

        resetSelect(kabupaten, 'Pilih Kabupaten / Kota');
        resetSelect(kecamatan, 'Pilih Kecamatan');
        resetSelect(desa, 'Pilih Desa / Kelurahan');

        if (this.value) {
            loadRegions(
                this.value,
                'kabupaten',
                kabupaten,
                'Pilih Kabupaten / Kota'
            );
        }
    });

    kabupaten.addEventListener('change', function () {

        resetSelect(kecamatan, 'Pilih Kecamatan');
        resetSelect(desa, 'Pilih Desa / Kelurahan');

        if (this.value) {
            loadRegions(
                this.value,
                'kecamatan',
                kecamatan,
                'Pilih Kecamatan'
            );
        }
    });

    kecamatan.addEventListener('change', function () {

        resetSelect(desa, 'Pilih Desa / Kelurahan');

        if (this.value) {
            loadRegions(
                this.value,
                'desa',
                desa,
                'Pilih Desa / Kelurahan'
            );
        }
    });

});
</script>
@endsection