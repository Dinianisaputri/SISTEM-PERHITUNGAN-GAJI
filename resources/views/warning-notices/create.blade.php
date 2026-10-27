@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
    <div class="mb-6">
        <a href="{{ route('warning-notices.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-emerald-700">
            ← Kembali ke Surat Teguran
        </a>

        <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-800">
            Buat Surat Teguran
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Isi data surat teguran karyawan dengan benar.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Periksa kembali input berikut:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('warning-notices.store') }}" method="POST">
        @csrf

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="font-semibold text-slate-800">Informasi Surat</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Data utama surat teguran.
                </p>
            </div>

            <div class="space-y-5 p-6">
                <div>
                    <label for="employee_search" class="mb-2 block text-sm font-medium text-slate-700">
                        Karyawan <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="employee_search"
                        value="{{ old('employee_search') }}"
                        placeholder="Cari nama atau NIK karyawan..."
                        autocomplete="off"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >

                    <select
                        name="employee_id"
                        id="employee_id"
                        required
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach ($employees as $employee)
                            <option
                                value="{{ $employee->id }}"
                                data-search="{{ strtolower(($employee->name ?? '') . ' ' . ($employee->no_induk_karyawan ?? '')) }}"
                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                            >
                                {{ $employee->name }} — NIK: {{ $employee->no_induk_karyawan ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="nomor_surat" class="mb-2 block text-sm font-medium text-slate-700">
                            Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nomor_surat"
                            id="nomor_surat"
                            value="{{ old('nomor_surat') }}"
                            placeholder="Contoh: 016/ST/HRD-SFI/IX/2026"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >
                        <p class="mt-1.5 text-xs text-slate-500">Nomor surat diisi manual oleh HRD.</p>
                    </div>

                    <div>
                        <label for="tanggal_surat" class="mb-2 block text-sm font-medium text-slate-700">
                            Tanggal Surat <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_surat"
                            id="tanggal_surat"
                            value="{{ old('tanggal_surat', now()->format('Y-m-d')) }}"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >
                    </div>
                </div>

                <div>
                    <label for="alasan_pelanggaran" class="mb-2 block text-sm font-medium text-slate-700">
                        Alasan Pelanggaran <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="alasan_pelanggaran"
                        id="alasan_pelanggaran"
                        rows="4"
                        required
                        placeholder="Jelaskan pelanggaran yang dilakukan karyawan..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-3 text-sm outline-none placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >{{ old('alasan_pelanggaran') }}</textarea>
                </div>
            </div>
        </div>

        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="font-semibold text-slate-800">Masa Berlaku dan Persetujuan</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Tanggal dan nama kepala unit diisi sesuai keputusan HRD.
                </p>
            </div>

            <div class="space-y-5 p-6">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="tanggal_mulai_berlaku" class="mb-2 block text-sm font-medium text-slate-700">
                            Mulai Berlaku <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_mulai_berlaku"
                            id="tanggal_mulai_berlaku"
                            value="{{ old('tanggal_mulai_berlaku') }}"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >
                    </div>

                    <div>
                        <label for="tanggal_akhir_berlaku" class="mb-2 block text-sm font-medium text-slate-700">
                            Akhir Berlaku <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_akhir_berlaku"
                            id="tanggal_akhir_berlaku"
                            value="{{ old('tanggal_akhir_berlaku') }}"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >
                    </div>
                </div>

                <div>
                    <label for="nama_kepala_unit" class="mb-2 block text-sm font-medium text-slate-700">
                        Nama Kepala Unit <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_kepala_unit"
                        id="nama_kepala_unit"
                        value="{{ old('nama_kepala_unit') }}"
                        placeholder="Masukkan nama kepala unit"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                    <p class="mt-1.5 text-xs text-slate-500">
                        Nama kepala unit diisi manual karena berbeda-beda.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse justify-end gap-3 sm:flex-row">
            <a
                href="{{ route('warning-notices.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
            >
                Batal
            </a>
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
            >
                Simpan Surat Teguran
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('employee_search');
    const employeeSelect = document.getElementById('employee_id');
    const options = Array.from(employeeSelect.options);

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();

        options.forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = !option.dataset.search.includes(keyword);
        });

        const selectedOption = employeeSelect.selectedOptions[0];

        if (selectedOption && selectedOption.hidden) {
            employeeSelect.value = '';
        }
    });
});
</script>
@endsection