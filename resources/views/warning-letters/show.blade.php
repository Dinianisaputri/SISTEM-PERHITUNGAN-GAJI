<x-app-layout>
    <div class="mx-auto max-w-4xl space-y-6 px-6 py-8">
        <div>
            <a href="{{ route('warning-letters.index') }}"
               class="text-sm text-emerald-700 hover:underline">
                ← Kembali ke daftar SP
            </a>
            <a href="{{ route('warning-letters.edit', $warningLetter) }}"
            class="mt-3 inline-block rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                Edit Surat
            </a>
            <a
                href="{{ route('warning-letters.print', $warningLetter) }}"
                target="_blank"
                class="mt-3 inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Cetak PDF
            </a>
            <form
                action="{{ route('warning-letters.destroy', $warningLetter) }}"
                method="POST"
                class="mt-3"
                onsubmit="return confirm('Yakin ingin menghapus Surat Peringatan ini? Data yang dihapus tidak dapat dikembalikan.')"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100"
                >
                    Hapus Surat
                </button>
            </form>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Detail Surat Peringatan
            </h1>
        </div>

        <div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-sm text-slate-500">Nomor Surat</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $warningLetter->nomor_surat }}
                </p>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Nama Karyawan</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $warningLetter->employee->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Nomor Induk Karyawan</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $warningLetter->employee->no_induk_karyawan ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Tanggal Surat</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $warningLetter->tanggal_surat->format('d-m-Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Tingkat SP</p>
                    <p class="mt-1 font-medium text-slate-800">
                        SP {{ $warningLetter->tingkat_sp }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Mulai Berlaku</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $warningLetter->tanggal_mulai_berlaku->format('d-m-Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Akhir Berlaku</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $warningLetter->tanggal_akhir_berlaku?->format('d-m-Y') ?? 'Tidak ditentukan' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Status</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ ucfirst($warningLetter->status) }}
                    </p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-5">
                <p class="text-sm text-slate-500">Alasan Pelanggaran</p>
                <p class="mt-2 whitespace-pre-line text-slate-800">
                    {{ $warningLetter->alasan_pelanggaran }}
                </p>
            </div>

            @if ($warningLetter->keterangan)
                <div>
                    <p class="text-sm text-slate-500">Keterangan Tambahan</p>
                    <p class="mt-2 whitespace-pre-line text-slate-800">
                        {{ $warningLetter->keterangan }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>