@extends('layouts.app')

@section('title', 'Arsip Gaji')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">
            Arsip Gaji
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Daftar arsip gaji yang sudah disimpan.
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600">
                    <th class="px-4 py-3">No.</th>
                    <th class="px-4 py-3">Nama Arsip</th>
                    <th class="px-4 py-3">Periode</th>
                    <th class="px-4 py-3">Jumlah Karyawan</th>
                    <th class="px-4 py-3">Total Gaji</th>
                    <th class="px-4 py-3">Tanggal Diarsipkan</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($archives as $archive)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $archive->archive_name }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3">
                            {{ \Carbon\Carbon::parse($archive->start_date)->format('d-m-Y') }}
                            -
                            {{ \Carbon\Carbon::parse($archive->end_date)->format('d-m-Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $archive->total_employees }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3">
                            Rp {{ number_format((float) $archive->total_salary, 0, ',', '.') }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3">
                            {{ $archive->created_at?->format('d-m-Y H:i') ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('salary_archives.show', $archive) }}"
                                    class="inline-flex rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    Detail
                                </a>

                                <button
                                    type="button"
                                    class="inline-flex rounded-lg border border-red-300 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-50"
                                    onclick="if (confirm('Yakin ingin menghapus arsip gaji ini? Data detail arsip terkait juga akan dihapus.')) document.getElementById('delete-archive-{{ $archive->id }}').submit();"
                                >
                                    Hapus
                                </button>

                                <form
                                    id="delete-archive-{{ $archive->id }}"
                                    action="{{ route('salary_archives.destroy', $archive) }}"
                                    method="POST"
                                    class="hidden"
                                >
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                            Belum ada arsip gaji.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        <a
            href="{{ route('salary.index') }}"
            class="inline-flex rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
        >
            Kembali ke Gaji Karyawan
        </a>
    </div>
</div>
@endsection
