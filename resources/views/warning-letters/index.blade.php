
<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-6 px-6 py-8">

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Surat Peringatan
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Daftar surat peringatan karyawan PT Starfood International.
                </p>
            </div>

            <a href="{{ route('warning-letters.create') }}"
               class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                + Buat Surat SP
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Nomor Surat</th>
                            <th class="px-5 py-4">Nama Karyawan</th>
                            <th class="px-5 py-4">Tingkat SP</th>
                            <th class="px-5 py-4">Tanggal Surat</th>
                            <th class="px-5 py-4">Masa Berlaku</th>
                            <th class="px-5 py-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($warningLetters as $letter)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    {{ $warningLetters->firstItem() + $loop->index }}
                                </td>
                                <td class="px-5 py-4 font-medium text-slate-800">
                                    {{ $letter->nomor_surat }}
                                </td>
                                <td class="px-5 py-4">
                                    {{ $letter->employee->name ?? '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    SP {{ $letter->tingkat_sp }}
                                </td>
                                <td class="px-5 py-4">
                                    {{ $letter->tanggal_surat->format('d-m-Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    {{ $letter->tanggal_mulai_berlaku->format('d-m-Y') }}
                                    —
                                    {{ $letter->tanggal_akhir_berlaku?->format('d-m-Y') ?? 'Tidak ditentukan' }}
                                </td>
                                
                                <td class="px-5 py-4">
                                <a
                                    href="{{ route('warning-letters.show', $letter) }}"
                                    class="font-medium text-emerald-700 hover:underline"
                                >
                                    Detail
                                </a>
                            </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500">
                                    Belum ada Surat Peringatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $warningLetters->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
