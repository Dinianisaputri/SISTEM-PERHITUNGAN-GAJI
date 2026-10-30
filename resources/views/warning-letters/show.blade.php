
<x-app-layout>
    <div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
<a href="{{ request('return_to', route('warning-letters.index')) }}"
   class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
    ← {{ request('return_to') ? 'Kembali ke Data Karyawan' : 'Kembali ke daftar SP' }}
</a>
                <h1 class="mt-3 text-2xl font-bold text-slate-800">
                    Detail Surat Peringatan
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Informasi surat peringatan karyawan PT Starfood International.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('warning-letters.edit', $warningLetter) }}"
                   class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-600">
                    Edit Surat
                </a>

                <a href="{{ route('warning-letters.print', $warningLetter) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                    Cetak PDF
                </a>

                <form action="{{ route('warning-letters.destroy', $warningLetter) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus Surat Peringatan ini? Data yang dihapus tidak dapat dikembalikan.')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">
                        Hapus Surat
                    </button>
                </form>
            </div>
        </div>

        {{-- INFORMASI SURAT --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-slate-800">
                            Informasi Surat
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Nomor surat dan informasi masa berlaku.
                        </p>
                    </div>

                    @php
                        $status = strtolower($warningLetter->status ?? '');
                    @endphp

                    <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold
                        {{ $status === 'aktif'
                            ? 'bg-emerald-100 text-emerald-700'
                            : ($status === 'berakhir'
                                ? 'bg-slate-100 text-slate-600'
                                : 'bg-amber-100 text-amber-700') }}">
                        {{ ucfirst($warningLetter->status ?? '-') }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <p class="text-sm text-slate-500">Nomor Surat</p>
                    <p class="mt-1 break-words font-semibold text-slate-800">
                        {{ $warningLetter->nomor_surat }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Tingkat Peringatan</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ strtoupper($warningLetter->tingkat_sp ?? '-') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Tanggal Surat</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $warningLetter->tanggal_surat?->format('d-m-Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Mulai Berlaku</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $warningLetter->tanggal_mulai_berlaku?->format('d-m-Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Akhir Berlaku</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $warningLetter->tanggal_akhir_berlaku?->format('d-m-Y') ?? 'Belum ditentukan' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- DATA KARYAWAN --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="font-semibold text-slate-800">
                    Data Karyawan
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Identitas karyawan yang menerima surat.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Nama Karyawan</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $warningLetter->employee->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Nomor Induk Karyawan</p>
                    <p class="mt-1 font-medium text-slate-800">
                        {{ $warningLetter->employee->no_induk_karyawan ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ALASAN PELANGGARAN --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="font-semibold text-slate-800">
                    Alasan Pelanggaran
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Uraian pelanggaran yang menjadi dasar penerbitan surat.
                </p>
            </div>

            <div class="p-6">
                <p class="whitespace-pre-line leading-7 text-slate-700">{{ $warningLetter->alasan_pelanggaran ?: 'Belum ada keterangan pelanggaran.' }}</p>
            </div>
        </div>

        {{-- KETERANGAN TAMBAHAN --}}
        @if ($warningLetter->keterangan)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="font-semibold text-slate-800">
                        Keterangan Tambahan
                    </h2>
                </div>

                <div class="p-6">
                    <p class="whitespace-pre-line leading-7 text-slate-700">{{ $warningLetter->keterangan }}</p>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
