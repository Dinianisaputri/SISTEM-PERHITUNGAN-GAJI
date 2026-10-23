@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('employees.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Kembali ke Data Karyawan
                </a>

                <h2 class="text-2xl font-bold text-gray-800 mt-3">
                    Edit Karyawan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Perbarui data karyawan PT Starfood International.
                </p>
            </div>

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

            <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-5">Data Identitas</h3>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Pas Foto
    </label>

    <div class="flex items-center gap-4">
        <div class="h-48 w-36 overflow-hidden rounded-xl border border-gray-200 bg-gray-50 shadow-sm">
            @if($employee->pas_foto)
                <img
                    src="{{ Storage::url($employee->pas_foto) }}"
                    alt="Pas Foto {{ $employee->name }}"
                    class="h-full w-full object-cover"
                >
            @else
                <div class="flex h-full w-full items-center justify-center text-center text-xs text-gray-500">
                    No Photo
                </div>
            @endif
        </div>

        <div class="text-sm text-gray-">
            <p class="font-medium text-gray-700">Pas Foto Karyawan</p>
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
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Karyawan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $employee->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Induk Karyawan</label>
                            <input type="text" name="no_induk_karyawan" value="{{ old('no_induk_karyawan', $employee->no_induk_karyawan) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Nomor induk karyawan">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PIN Fingerprint</label>
                            <input type="text" name="fingerprint_pin" value="{{ old('fingerprint_pin', $employee->fingerprint_pin) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="PIN fingerprint">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $employee->jabatan) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Operator">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bagian <span class="text-red-500">*</span></label>
                            <select name="bagian" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Bagian</option>
                                @foreach($bagianList as $bagian)
                                    <option value="{{ $bagian }}" {{ old('bagian', $employee->bagian) == $bagian ? 'selected' : '' }}>{{ $bagian }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Karyawan</label>
                            <select name="jenis_karyawan" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Jenis</option>
                                <option value="Borongan" {{ old('jenis_karyawan', $employee->jenis_karyawan) == 'Borongan' ? 'selected' : '' }}>Borongan</option>
                                <option value="Bulanan" {{ old('jenis_karyawan', $employee->jenis_karyawan) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="Harian" {{ old('jenis_karyawan', $employee->jenis_karyawan) == 'Harian' ? 'selected' : '' }}>Harian</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan</label>
                            <select name="pendidikan" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Pendidikan</option>
                                @foreach(['SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $item)
                                    <option value="{{ $item }}" {{ old('pendidikan', $employee->pendidikan) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pernikahan</label>
                            <select name="status_pernikahan" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Status</option>
                                <option value="Belum Menikah" {{ old('status_pernikahan', $employee->status_pernikahan) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="Sudah Menikah" {{ old('status_pernikahan', $employee->status_pernikahan) == 'Sudah Menikah' ? 'selected' : '' }}>Sudah Menikah</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $employee->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $employee->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $employee->tempat_lahir) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Lamongan">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $employee->tanggal_lahir?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. KTP</label>
                            <input type="text" name="no_ktp" value="{{ old('no_ktp', $employee->no_ktp) }}" maxlength="16" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="16 digit nomor KTP">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Domisili</label>
                            <textarea name="alamat_domisili" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Masukkan alamat domisili">{{ old('alamat_domisili', $employee->alamat_domisili) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-5">Data Pekerjaan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mulai Kerja</label>
                            <input type="date" name="mulai_kerja" value="{{ old('mulai_kerja', $employee->mulai_kerja?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kontrak</label>
                            <input type="date" name="tanggal_kontrak" value="{{ old('tanggal_kontrak', $employee->tanggal_kontrak?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Selesai Kontrak</label>
                            <input type="date" name="selesai_kontrak" value="{{ old('selesai_kontrak', $employee->selesai_kontrak?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kontrak Ke-</label>
                            <input type="number" name="kontrak_ke" value="{{ old('kontrak_ke', $employee->kontrak_ke) }}" min="1" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 1">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah SP</label>
                            <input type="number" name="jumlah_sp" value="{{ old('jumlah_sp', $employee->jumlah_sp ?? 0) }}" min="0" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="0">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Karyawan</label>
                            <select name="is_active" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="1" {{ old('is_active', $employee->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $employee->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Dokumen Karyawan</h3>
                    <p class="text-sm text-gray-500 mb-5">Upload dokumen dalam format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB per file.</p>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                        @foreach(['pas_foto' => 'Pas Foto', 'foto_kk' => 'Foto KK', 'foto_ktp' => 'Foto KTP', 'foto_biodata' => 'Foto Biodata', 'surat_kontrak' => 'Surat Kontrak'] as $field => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                                <input type="file" name="{{ $field }}" accept="{{ $field === 'pas_foto' ? '.jpg,.jpeg,.png' : '.jpg,.jpeg,.png,.pdf' }}" class="w-full text-sm border border-gray-300 rounded-lg p-2">
                                @if($employee->$field)
                                    <div class="mt-2 text-xs text-blue-600">
                                        <a href="{{ Storage::url($employee->$field) }}" target="_blank" class="underline">Lihat file lama</a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('employees.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection