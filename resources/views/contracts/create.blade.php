@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">

    <div class="mb-6">
        <a href="{{ route('contracts.index') }}"
           class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
            ← Kembali ke Surat Kontrak
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-800">
            Tambah Surat Kontrak
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Masukkan data kontrak kerja karyawan PT Starfood International.
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

    <form action="{{ route('contracts.store') }}" method="POST"
          class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @csrf

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-800">Informasi Kontrak</h2>
            <p class="mt-1 text-sm text-slate-500">
                Lengkapi identitas karyawan dan periode kontrak.
            </p>
        </div>
 <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
<div>
    <label for="nomor_kontrak" class="mb-2 block text-sm font-medium text-slate-700">
        Nomor Kontrak <span class="text-red-500">*</span>
    </label>

    <input
        type="text"
        id="nomor_kontrak"
        name="nomor_kontrak"
        value="{{ old('nomor_kontrak') }}"
        required
        placeholder="Contoh: PKWT/001/2026"
        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
    >

    <p class="mt-1 text-xs text-slate-500">
        Masukkan nomor kontrak sesuai dokumen resmi.
    </p>
</div>

            
<div class="md:col-span-2">
    <label for="employee_search" class="mb-2 block text-sm font-medium text-slate-700">
        Karyawan <span class="text-red-500">*</span>
    </label>

    <input
        type="text"
        id="employee_search"
        list="employees-list"
        placeholder="Ketik NIK atau nama karyawan..."
        autocomplete="off"
        required
        value="{{ old('employee_id') ? optional($employees->firstWhere('id', old('employee_id')))->no_induk_karyawan . ' — ' . optional($employees->firstWhere('id', old('employee_id')))->name : '' }}"
        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
    >

    <datalist id="employees-list">
        @foreach ($employees as $employee)
            <option
                value="{{ $employee->no_induk_karyawan ?? '-' }} — {{ $employee->name }}"
                data-id="{{ $employee->id }}">
            </option>
        @endforeach
    </datalist>

    <input
        type="hidden"
        id="employee_id"
        name="employee_id"
        value="{{ old('employee_id') }}"
    >

    <p id="employee-hint" class="mt-1 text-xs text-slate-500">
        Ketik nama atau NIK, lalu pilih karyawan yang sesuai dari saran.
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
                            @selected(old('jenis_kontrak') === $jenis)>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
            </div>           
<div>
    <label for="bagian" class="mb-2 block text-sm font-medium text-slate-700">
        Unit Kerja <span class="text-red-500">*</span>
    </label>

    <select id="bagian" name="bagian" required
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">

        <option value="">-- Pilih Unit Kerja --</option>

        @foreach ([
            'Frozen Fish',
            'Fish Meal',
            'Surimi',
            'Value Added',
            'Office',
            'Satpam',
        ] as $unit)
            <option value="{{ $unit }}"
                @selected(old('bagian') === $unit)>
                {{ $unit }}
            </option>
        @endforeach
    </select>

    <p class="mt-1 text-xs text-slate-500">
        Pilih unit kerja sesuai penempatan karyawan dalam kontrak.
    </p>
</div>
            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                    Status Kontrak <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="berakhir" @selected(old('status') === 'berakhir')>Berakhir</option>
                    <option value="dibatalkan" @selected(old('status') === 'dibatalkan')>Dibatalkan</option>
                </select>
            </div>

            <div>
                <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-slate-700">
                    Tanggal Dibuat <span class="text-red-500">*</span>
                </label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                       value="{{ old('tanggal_mulai') }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            </div>

            <div>
                <label for="tanggal_berakhir" class="mb-2 block text-sm font-medium text-slate-700">
                    Tanggal Berakhir
                </label>
                <input type="date" id="tanggal_berakhir" name="tanggal_berakhir"
                       value="{{ old('tanggal_berakhir') }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <p class="mt-1 text-xs text-slate-500">
                    Kosongkan jika belum ditentukan.
                </p>
            </div>

            <div class="md:col-span-2">
                <label for="keterangan" class="mb-2 block text-sm font-medium text-slate-700">
                    Keterangan
                </label>
                <textarea id="keterangan" name="keterangan" rows="4"
                          placeholder="Catatan tambahan jika diperlukan..."
                          class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">{{ old('keterangan') }}</textarea>
            </div>
        </div>
        
{{-- Dokumen Surat Kontrak --}}
<div class="mb-5">
    <label for="file_surat" class="block text-sm font-medium text-gray-700 mb-2">
        Dokumen Surat Kontrak
    </label>

    <input
        type="file"
        name="file_surat"
        id="file_surat"
        accept=".pdf,.jpg,.jpeg,.png"
        class="block w-full rounded-lg border border-gray-300 p-2 text-sm"
    >

    <p class="mt-1 text-xs text-gray-500">
        Format PDF, JPG, JPEG, atau PNG. Maksimal 5 MB.
        Boleh dikosongkan jika surat asli belum tersedia.
    </p>
    
</div>


        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
            <a href="{{ route('contracts.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-center text-sm font-medium text-slate-700 hover:bg-slate-100">
                Batal
            </a>
            <button type="submit"
                    class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                Simpan Kontrak
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('employee_search');
    const employeeId = document.getElementById('employee_id');
    const hint = document.getElementById('employee-hint');

    if (!search || !employeeId || !hint) return;

    const options = Array.from(
        document.querySelectorAll('#employees-list option')
    );

    function syncEmployee() {
        const selected = options.find(option =>
            option.value.trim() === search.value.trim()
        );

        employeeId.value = selected
            ? selected.dataset.id
            : '';

        if (selected) {
            hint.textContent = 'Karyawan terpilih: ' + selected.value;
            hint.className = 'mt-1 text-xs text-emerald-600';
            search.setCustomValidity('');
        } else {
            hint.textContent =
                'Pilih nama dan NIK dari saran yang tersedia.';
            hint.className = 'mt-1 text-xs text-amber-600';
        }
    }

    search.addEventListener('input', syncEmployee);
    search.addEventListener('change', syncEmployee);

    search.closest('form').addEventListener('submit', function (event) {
        syncEmployee();

        if (!employeeId.value) {
            event.preventDefault();
            search.setCustomValidity(
                'Pilih karyawan dari saran yang tersedia.'
            );
            search.reportValidity();
        }
    });

    syncEmployee();
});
</script>

@endsection

