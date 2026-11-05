
@extends('layouts.app')

@section('title', 'Detail Arsip Gaji')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Detail Arsip Gaji
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $salaryArchive->archive_name }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if ($salaryArchive->archive_type === 'salary')
                <a
                    href="{{ route('salary_archives.slipPdf', $salaryArchive->id) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700"
                >
                    Cetak Semua Slip Gaji (PDF)
                </a>

                <a
                    href="{{ route('salary_archives.exportFinance', $salaryArchive->id) }}"
                    class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                >
                    Export Data Gaji (Excel)
                </a>
            @endif
        </div>
    </div>

    {{-- RINGKASAN ARSIP --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Periode</p>
            <p class="mt-2 font-semibold text-gray-800">
                {{ $salaryArchive->start_date
                    ? \Carbon\Carbon::parse($salaryArchive->start_date)->format('d-m-Y')
                    : '-' }}
                s.d.
                {{ $salaryArchive->end_date
                    ? \Carbon\Carbon::parse($salaryArchive->end_date)->format('d-m-Y')
                    : '-' }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Jumlah Karyawan</p>
            <p class="mt-2 text-2xl font-semibold text-gray-800">
                {{ $salaryArchive->total_employees ?? 0 }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Gaji</p>
            <p class="mt-2 text-xl font-semibold text-gray-800">
                Rp {{ number_format((float) $salaryArchive->total_salary, 0, ',', '.') }}
            </p>
        </div>

    </div>

    {{-- TABEL DETAIL ARSIP --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">

            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600">
                    <th class="px-4 py-3">No.</th>

                    @if ($salaryArchive->archive_type === 'salary')
                        <th class="px-4 py-3">PIN</th>
                        <th class="px-4 py-3">Karyawan</th>
                        <th class="px-4 py-3">Bagian</th>
                        <th class="px-4 py-3">Hari Hadir</th>
                        <th class="px-4 py-3">Jam Kerja</th>
                        <th class="px-4 py-3">Gaji Pokok</th>
                        <th class="px-4 py-3">Total Lembur</th>
                        <th class="px-4 py-3">Potongan</th>
                        <th class="px-4 py-3">Nominal Tambahan</th>
                        <th class="px-4 py-3">Gaji Bersih</th>
                        <th class="px-4 py-3">Aksi</th>
                    @else
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Karyawan</th>
                        <th class="px-4 py-3">Jam Masuk</th>
                        <th class="px-4 py-3">Jam Pulang</th>
                        <th class="px-4 py-3">Jam Kerja</th>
                        <th class="px-4 py-3">Gaji</th>
                    @endif
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                {{-- ARSIP GAJI --}}
                @if ($salaryArchive->archive_type === 'salary')

                    @forelse ($archiveDetails as $detail)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $detail->fingerprint_pin ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $detail->employee_name ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $detail->bagian ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $detail->hari_hadir ?? 0 }}
                            </td>

                            <td class="px-4 py-3">
                                {{ number_format((float) ($detail->jam_kerja ?? 0), 2, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                Rp {{ number_format((float) ($detail->gaji_pokok ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                Rp {{ number_format((float) ($detail->total_lembur ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                Rp {{ number_format((float) ($detail->total_potongan ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                Rp {{ number_format((float) ($detail->total_tambahan ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 font-semibold">
                                Rp {{ number_format((float) ($detail->gaji_bersih ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                <a
                                    href="{{ route('salary_archives.details.edit', [
                                        'salaryArchive' => $salaryArchive->id,
                                        'detail' => $detail->id,
                                    ]) }}"
                                    class="inline-flex rounded-lg border border-teal-700 px-3 py-1.5 text-sm font-medium text-teal-700 hover:bg-teal-50"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-10 text-center text-gray-500">
                                Belum ada detail gaji pada arsip ini.
                            </td>
                        </tr>
                    @endforelse

                {{-- ARSIP CHECKLOG --}}
                @else

                    @forelse ($checklogs as $checklog)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ $loop->iteration }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                {{ $checklog->tanggal
                                    ? \Carbon\Carbon::parse($checklog->tanggal)->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $checklog->employee->name
                                    ?? $checklog->nama_karyawan
                                    ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $checklog->scan_masuk ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $checklog->scan_pulang ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $checklog->total_jam_kerja ?? 0 }} jam
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                Rp {{ number_format((float) ($checklog->gaji ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                                Belum ada detail checklog pada arsip ini.
                            </td>
                        </tr>
                    @endforelse

                @endif

            </tbody>
        </table>
    </div>

</div>
@endsection
