@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
    <div class="mb-6">
        <a href="{{ route('warning-notices.show', $warningNotice) }}"
           class="text-sm font-medium text-slate-500 transition hover:text-emerald-700">
            ← Kembali ke Detail
        </a>

        <h1 class="mt-4 text-2xl font-bold text-slate-800">Edit Surat Teguran</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui informasi surat teguran.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('warning-notices.update', $warningNotice) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="font-semibold text-slate-800">Informasi Surat</h2>
                <p class="mt-1 text-sm text-slate-500">Periksa data sebelum menyimpan perubahan.</p>
            </div>

            <div class="space-y-5 p-6">
                <div>
                    <label for="employee_search" class="mb-2 block text-sm font-medium text-slate-700">
                        Karyawan <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="employee_search"
                        autocomplete="off"
                        value="{{ old('employee_search', ($warningNotice->employee->name ?? '') . ' — NIK: ' . ($warningNotice->employee->no_induk_karyawan ?? '-')) }}"
                        placeholder="Cari nama atau NIK karyawan..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >

                    <select name="employee_id" id="employee_id" required
                            class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        @foreach ($employees as $employee)
                            <option
                                value="{{ $employee->id }}"
                                data-search="{{ strtolower(($employee->name ?? '') . ' ' . ($employee->no_induk_karyawan ?? '')) }}"
                                {{ old('employee_id', $warningNotice->employee_id) == $employee->id ? 'selected' : '' }}
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
                        <input type="text" name="nomor_surat" id="nomor_surat"
                               value="{{ old('nomor_surat', $warningNotice->nomor_surat) }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label for="tanggal_surat" class="mb-2 block text-sm font-medium text-slate-700">
                            Tanggal Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_surat" id="tanggal_surat"
                               value="{{ old('tanggal_surat', $warningNotice->tanggal_surat->format('Y-m-d')) }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    </div>
                </div>

                <div>
                    <label for="alasan_pelanggaran" class="mb-2 block text-sm font-medium text-slate-700">
                        Alasan Pelanggaran <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan_pelanggaran" id="alasan_pelanggaran" rows="4" required
                              class="w-full rounded-lg border border-slate-300 px-3 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">{{ old('alasan_pelanggaran', $warningNotice->alasan_pelanggaran) }}</textarea>
                </div>
            </div>
        </div>

        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="font-semibold text-slate-800">Masa Berlaku dan Kepala Unit</h2>
            </div>

            <div class="space-y-5 p-6">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="tanggal_mulai_berlaku" class="mb-2 block text-sm font-medium text-slate-700">
                            Mulai Berlaku <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai_berlaku" id="tanggal_mulai_berlaku"
                               value="{{ old('tanggal_mulai_berlaku', $warningNotice->tanggal_mulai_berlaku->format('Y-m-d')) }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label for="tanggal_akhir_berlaku" class="mb-2 block text-sm font-medium text-slate-700">
                            Akhir Berlaku <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_akhir_berlaku" id="tanggal_akhir_berlaku"
                               value="{{ old('tanggal_akhir_berlaku', $warningNotice->tanggal_akhir_berlaku->format('Y-m-d')) }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    </div>
                </div>

                <div>
                    <label for="nama_kepala_unit" class="mb-2 block text-sm font-medium text-slate-700">
                        Nama Kepala Unit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kepala_unit" id="nama_kepala_unit"
                           value="{{ old('nama_kepala_unit', $warningNotice->nama_kepala_unit) }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse justify-end gap-3 sm:flex-row">
            <a href="{{ route('warning-notices.show', $warningNotice) }}"
               class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                    class="rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                Simpan Perubahan
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
            option.hidden = option.value !== '' &&
                !option.dataset.search.includes(keyword);
        });

        const selectedOption = employeeSelect.selectedOptions[0];

        if (selectedOption && selectedOption.hidden) {
            employeeSelect.value = '';
        }
    });
});
</script>
@endsection