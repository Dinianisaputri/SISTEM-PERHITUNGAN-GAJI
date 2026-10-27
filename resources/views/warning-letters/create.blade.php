
<x-app-layout>
    <div class="mx-auto max-w-5xl space-y-6 px-6 py-8">

        <div>
            <a href="{{ route('warning-letters.index') }}"
               class="text-sm text-emerald-700 hover:underline">
                ← Kembali ke daftar SP
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Buat Surat Peringatan
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Masukkan data surat peringatan karyawan.
            </p>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold">Ada data yang perlu diperbaiki:</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('warning-letters.store') }}"
              method="POST"
              class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">

               
<div>
    <label for="employee_search" class="mb-2 block text-sm font-medium text-slate-700">
        Karyawan
    </label>

    <div class="relative">
        <input
            id="employee_search"
            type="text"
            autocomplete="off"
            placeholder="Ketik nama atau nomor induk karyawan..."
            class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
            value="{{ old('employee_id') ? optional($employees->firstWhere('id', old('employee_id')))->name : '' }}"
        >

        <input
            type="hidden"
            id="employee_id"
            name="employee_id"
            value="{{ old('employee_id') }}"
        >

        <div
            id="employee_options"
            class="absolute z-50 mt-1 hidden max-h-60 w-full overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-lg"
        >
            @foreach ($employees as $employee)
                <button
                    type="button"
                    class="employee-option block w-full px-4 py-3 text-left text-sm hover:bg-emerald-50"
                    data-id="{{ $employee->id }}"
                    data-name="{{ $employee->name }}"
                    data-search="{{ strtolower($employee->name . ' ' . $employee->no_induk_karyawan . ' ' . $employee->fingerprint_pin) }}"
                >
                    <span class="block font-medium text-slate-800">
                        {{ $employee->name }}
                    </span>
                    <span class="text-xs text-slate-500">
                        NIK: {{ $employee->no_induk_karyawan ?: '-' }}
                        · PIN: {{ $employee->fingerprint_pin ?: '-' }}
                    </span>
                </button>
            @endforeach

            <p id="employee_empty" class="hidden px-4 py-3 text-sm text-slate-500">
                Karyawan tidak ditemukan.
            </p>
        </div>
    </div>

    <p class="mt-1 text-xs text-slate-500">
        Ketik nama, NIK, atau PIN, lalu pilih karyawan dari daftar.
    </p>

    @error('employee_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

                <div>
                    <label for="nomor_surat" class="mb-2 block text-sm font-medium text-slate-700">
                        Nomor Surat
                    </label>
                    <input id="nomor_surat" name="nomor_surat" type="text"
                           value="{{ old('nomor_surat') }}" required
                           placeholder="Contoh: 016/ST/HRD-SFI/IX/2026"
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="tanggal_surat" class="mb-2 block text-sm font-medium text-slate-700">
                        Tanggal Surat
                    </label>
                    <input id="tanggal_surat" name="tanggal_surat" type="date"
                           value="{{ old('tanggal_surat') }}" required
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="tingkat_sp" class="mb-2 block text-sm font-medium text-slate-700">
                        Tingkat SP
                    </label>
                    <select id="tingkat_sp" name="tingkat_sp" required
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Pilih tingkat SP</option>
                        <option value="1" @selected(old('tingkat_sp') == '1')>SP I</option>
                        <option value="2" @selected(old('tingkat_sp') == '2')>SP II</option>
                        <option value="3" @selected(old('tingkat_sp') == '3')>SP III</option>
                    </select>
                </div>

                <div>
                    <label for="tanggal_mulai_berlaku" class="mb-2 block text-sm font-medium text-slate-700">
                        Mulai Berlaku
                    </label>
                    <input id="tanggal_mulai_berlaku" name="tanggal_mulai_berlaku"
                           type="date" value="{{ old('tanggal_mulai_berlaku') }}" required
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="tanggal_akhir_berlaku" class="mb-2 block text-sm font-medium text-slate-700">
                        Akhir Berlaku
                    </label>
                    <input id="tanggal_akhir_berlaku" name="tanggal_akhir_berlaku"
                           type="date" value="{{ old('tanggal_akhir_berlaku') }}"
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="md:col-span-2">
                    <label for="alasan_pelanggaran" class="mb-2 block text-sm font-medium text-slate-700">
                        Alasan Pelanggaran
                    </label>
                    <textarea id="alasan_pelanggaran" name="alasan_pelanggaran"
                              rows="4" required
                              class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                              placeholder="Jelaskan alasan diterbitkannya SP">{{ old('alasan_pelanggaran') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="mb-2 block text-sm font-medium text-slate-700">
                        Keterangan Tambahan
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                              placeholder="Opsional">{{ old('keterangan') }}</textarea>
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('warning-letters.index') }}"
                   class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>

                <button type="submit"
                        class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                    Simpan Surat
                </button>
            </div>
        </form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('employee_search');
    const employeeId = document.getElementById('employee_id');
    const optionsBox = document.getElementById('employee_options');
    const emptyMessage = document.getElementById('employee_empty');
    const options = document.querySelectorAll('.employee-option');

    function filterEmployees() {
        const query = searchInput.value.trim().toLowerCase();
        let found = 0;

        optionsBox.classList.remove('hidden');

        options.forEach(function (option) {
            const matches = option.dataset.search.includes(query);

            option.classList.toggle('hidden', !matches);

            if (matches) found++;
        });

        emptyMessage.classList.toggle('hidden', found > 0);
    }

    searchInput.addEventListener('focus', filterEmployees);
    searchInput.addEventListener('input', function () {
        // Pilihan lama harus dipilih ulang jika teks diubah.
        employeeId.value = '';
        filterEmployees();
    });

    options.forEach(function (option) {
        option.addEventListener('click', function () {
            searchInput.value = option.dataset.name;
            employeeId.value = option.dataset.id;
            optionsBox.classList.add('hidden');
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('#employee_search') &&
            !event.target.closest('#employee_options')) {
            optionsBox.classList.add('hidden');
        }
    });

    document.querySelector('form').addEventListener('submit', function (event) {
        if (!employeeId.value) {
            event.preventDefault();
            searchInput.setCustomValidity('Pilih karyawan dari daftar terlebih dahulu.');
            searchInput.reportValidity();
        }
    });

    searchInput.addEventListener('input', function () {
        searchInput.setCustomValidity('');
    });
});
</script>

    </div>
</x-app-layout>