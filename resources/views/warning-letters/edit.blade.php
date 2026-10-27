
<x-app-layout>
    <div class="mx-auto max-w-5xl space-y-6 px-6 py-8">
        <div>
            <a href="{{ route('warning-letters.show', $warningLetter) }}"
               class="text-sm text-emerald-700 hover:underline">
                ← Kembali ke detail SP
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Edit Surat Peringatan
            </h1>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('warning-letters.update', $warningLetter) }}"
              method="POST"
              class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="employee_id" class="mb-2 block text-sm font-medium text-slate-700">
                        Karyawan
                    </label>
                    <select id="employee_id" name="employee_id" required
                            class="w-full rounded-lg border-slate-300 text-sm">
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}"
                                @selected(old('employee_id', $warningLetter->employee_id) == $employee->id)>
                                {{ $employee->name }}
                                — {{ $employee->no_induk_karyawan ?: '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nomor_surat" class="mb-2 block text-sm font-medium text-slate-700">
                        Nomor Surat
                    </label>
                    <input id="nomor_surat" name="nomor_surat" type="text" required
                           value="{{ old('nomor_surat', $warningLetter->nomor_surat) }}"
                           class="w-full rounded-lg border-slate-300 text-sm">
                </div>

                <div>
                    <label for="tanggal_surat" class="mb-2 block text-sm font-medium text-slate-700">
                        Tanggal Surat
                    </label>
                    <input id="tanggal_surat" name="tanggal_surat" type="date" required
                           value="{{ old('tanggal_surat', $warningLetter->tanggal_surat->format('Y-m-d')) }}"
                           class="w-full rounded-lg border-slate-300 text-sm">
                </div>

                <div>
                    <label for="tingkat_sp" class="mb-2 block text-sm font-medium text-slate-700">
                        Tingkat SP
                    </label>
                    <select id="tingkat_sp" name="tingkat_sp" required
                            class="w-full rounded-lg border-slate-300 text-sm">
                        <option value="1" @selected(old('tingkat_sp', $warningLetter->tingkat_sp) == 1)>SP I</option>
                        <option value="2" @selected(old('tingkat_sp', $warningLetter->tingkat_sp) == 2)>SP II</option>
                        <option value="3" @selected(old('tingkat_sp', $warningLetter->tingkat_sp) == 3)>SP III</option>
                    </select>
                </div>

                <div>
                    <label for="tanggal_mulai_berlaku" class="mb-2 block text-sm font-medium text-slate-700">
                        Mulai Berlaku
                    </label>
                    <input id="tanggal_mulai_berlaku" name="tanggal_mulai_berlaku"
                           type="date" required
                           value="{{ old('tanggal_mulai_berlaku', $warningLetter->tanggal_mulai_berlaku->format('Y-m-d')) }}"
                           class="w-full rounded-lg border-slate-300 text-sm">
                </div>

                <div>
                    <label for="tanggal_akhir_berlaku" class="mb-2 block text-sm font-medium text-slate-700">
                        Akhir Berlaku
                    </label>
                    <input id="tanggal_akhir_berlaku" name="tanggal_akhir_berlaku"
                           type="date"
                           value="{{ old('tanggal_akhir_berlaku', $warningLetter->tanggal_akhir_berlaku?->format('Y-m-d')) }}"
                           class="w-full rounded-lg border-slate-300 text-sm">
                </div>

                <div class="md:col-span-2">
                    <label for="alasan_pelanggaran" class="mb-2 block text-sm font-medium text-slate-700">
                        Alasan Pelanggaran
                    </label>
                    <textarea id="alasan_pelanggaran" name="alasan_pelanggaran"
                              rows="4" required
                              class="w-full rounded-lg border-slate-300 text-sm">{{ old('alasan_pelanggaran', $warningLetter->alasan_pelanggaran) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="mb-2 block text-sm font-medium text-slate-700">
                        Keterangan Tambahan
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="w-full rounded-lg border-slate-300 text-sm">{{ old('keterangan', $warningLetter->keterangan) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('warning-letters.show', $warningLetter) }}"
                   class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
