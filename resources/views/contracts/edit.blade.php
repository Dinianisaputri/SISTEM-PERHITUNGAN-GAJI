@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">

    <div class="mb-6">
        <a href="{{ route('contracts.show', $contract) }}"
           class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
            ← Kembali ke Detail Kontrak
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-800">
            Edit Surat Kontrak
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Perbarui data kontrak {{ $contract->nomor_kontrak }}.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Ada data yang perlu diperbaiki:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

   <form action="{{ route('contracts.update', $contract) }}" method="POST"
      enctype="multipart/form-data"
      class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-800">Informasi Kontrak</h2>
            <p class="mt-1 text-sm text-slate-500">
                Kolom bertanda <span class="text-red-500">*</span> wajib diisi.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

            <div class="md:col-span-2">
                <label for="employee_id" class="mb-2 block text-sm font-medium text-slate-700">
                    Karyawan <span class="text-red-500">*</span>
                </label>
                <select id="employee_id" name="employee_id" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}"
                            @selected((string) old('employee_id', $contract->employee_id) === (string) $employee->id)>
                            {{ $employee->no_induk_karyawan ?? '-' }}
                            — {{ $employee->name }}
                            @if ($employee->bagian)
                                ({{ $employee->bagian }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="nomor_kontrak" class="mb-2 block text-sm font-medium text-slate-700">
                    Nomor Kontrak <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nomor_kontrak" name="nomor_kontrak"
                       value="{{ old('nomor_kontrak', $contract->nomor_kontrak) }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            </div>
        <div>
            <label for="kontrak_ke" class="mb-2 block text-sm font-medium text-slate-700">
                Kontrak Ke-
            </label>

            <input
                type="number"
                id="kontrak_ke"
                name="kontrak_ke"
                min="1"
                step="1"
                value="{{ old('kontrak_ke', $contract->kontrak_ke) }}"
                placeholder="Contoh: 1, 2, 3, atau 4"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            >

            <p class="mt-1 text-xs text-slate-500">
                Isi nomor urutan kontrak sesuai data HRD. Boleh dikosongkan jika belum diketahui.
            </p>
        </div>
            <div>
                <label for="jenis_kontrak" class="mb-2 block text-sm font-medium text-slate-700">
                    Jenis Kontrak <span class="text-red-500">*</span>
                </label>
                <select id="jenis_kontrak" name="jenis_kontrak" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    <option value="">-- Pilih Jenis Kontrak --</option>
                    @foreach ([
                        'PKWT BO Jam FF',
                        'PKWT BO Jam FM',
                        'PKWT BO Jam SR',
                        'PKWT BO Jam VA',
                        'PKWT FF',
                        'PKWT FM',
                        'PKWT Harian',
                        'PKWT Satpam',
                    ] as $jenis)
                        <option value="{{ $jenis }}"
                            @selected(old('jenis_kontrak', $contract->jenis_kontrak) === $jenis)>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="bagian" class="mb-2 block text-sm font-medium text-slate-700">
                    Bagian / Departemen
                </label>
                <input type="text" id="bagian" name="bagian"
                       value="{{ old('bagian', $contract->bagian) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                    Status Kontrak <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    <option value="aktif" @selected(old('status', $contract->status) === 'aktif')>Aktif</option>
                    <option value="berakhir" @selected(old('status', $contract->status) === 'berakhir')>Berakhir</option>
                    <option value="dibatalkan" @selected(old('status', $contract->status) === 'dibatalkan')>Dibatalkan</option>
                </select>
            </div>

            <div>
                <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-slate-700">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                       value="{{ old('tanggal_mulai', $contract->tanggal_mulai?->format('Y-m-d')) }}"
                       required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            </div>

            <div>
                <label for="tanggal_berakhir" class="mb-2 block text-sm font-medium text-slate-700">
                    Tanggal Berakhir
                </label>
                <input type="date" id="tanggal_berakhir" name="tanggal_berakhir"
                       value="{{ old('tanggal_berakhir', $contract->tanggal_berakhir?->format('Y-m-d')) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <p class="mt-1 text-xs text-slate-500">
                    Kosongkan jika tanggal berakhir belum ditentukan.
                </p>
            </div>

            <div class="md:col-span-2">
                <label for="keterangan" class="mb-2 block text-sm font-medium text-slate-700">
                    Keterangan
                </label>
                <textarea id="keterangan" name="keterangan" rows="4"
                          class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">{{ old('keterangan', $contract->keterangan) }}</textarea>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
            <a href="{{ route('contracts.show', $contract) }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-center text-sm font-medium text-slate-700 hover:bg-slate-100">
                Batal
            </a>
            
{{-- Dokumen Surat Kontrak --}}
<div class="mb-5">
    <label for="file_surat" class="block text-sm font-medium text-gray-700 mb-2">
        Dokumen Surat Kontrak
    </label>

    @if ($contract->file_surat)
        <p class="mb-2 text-sm">
            Dokumen saat ini:
            <a
                href="{{ asset('storage/' . $contract->file_surat) }}"
                target="_blank"
                rel="noopener noreferrer"
                class="text-blue-600 hover:underline"
            >
                Lihat surat kontrak
            </a>
        </p>
    @else
        <p class="mb-2 text-sm text-gray-500">
            Belum ada dokumen yang diunggah.
        </p>
    @endif

    <input
        type="file"
        name="file_surat"
        id="file_surat"
        accept=".pdf,.jpg,.jpeg,.png"
        class="block w-full rounded-lg border border-gray-300 p-2 text-sm"
    >

    <p class="mt-1 text-xs text-gray-500">
        Kosongkan jika tidak ingin mengganti dokumen.
        Format PDF, JPG, JPEG, atau PNG. Maksimal 5 MB.
    </p>
</div>

            <button type="submit"
                    class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
