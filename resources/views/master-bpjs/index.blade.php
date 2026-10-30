@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6">
    <div class="mx-auto max-w-7xl">

        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Master BPJS
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Kelola data kepesertaan dan satu tarif BPJS untuk setiap karyawan.
                </p>
            </div>

            <a href="{{ route('salary.index') }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">
                Kembali ke Payroll
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-4 rounded-xl border border-slate-200 bg-white p-4">
            <form method="GET" action="{{ route('master-bpjs.index') }}"
                  class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama, NIK karyawan, atau PIN..."
                       class="min-w-0 rounded-lg border-slate-200 text-sm focus:border-slate-400 focus:ring-0">

                <select name="program_bpjs_tk"
                        class="rounded-lg border-slate-200 text-sm focus:border-slate-400 focus:ring-0">
                    <option value="">Semua Program BPJS TK</option>
                    <option value="2 Program" @selected($programBpjsTk === '2 Program')>
                        2 Program
                    </option>
                    <option value="Full Program" @selected($programBpjsTk === 'Full Program')>
                        Full Program
                    </option>
                </select>

                <select name="kelas_bpjs_kesehatan"
                        class="rounded-lg border-slate-200 text-sm focus:border-slate-400 focus:ring-0">
                    <option value="">Semua Kelas BPJS Kesehatan</option>
                    <option value="Kelas 1" @selected($kelasBpjsKesehatan === 'Kelas 1')>
                        Kelas 1
                    </option>
                    <option value="Kelas 2" @selected($kelasBpjsKesehatan === 'Kelas 2')>
                        Kelas 2
                    </option>
                </select>

                <button type="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                    Terapkan Filter
                </button>

                @if($search !== '' || $programBpjsTk !== '' || $kelasBpjsKesehatan !== '')
                    <a href="{{ route('master-bpjs.index') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Karyawan</th>
                            <th class="px-4 py-3">BPJS Ketenagakerjaan</th>
                            <th class="px-4 py-3">BPJS Kesehatan</th>
                            <th class="px-4 py-3">Tarif BPJS (Gabungan)</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500">
                                    {{ $employees->firstItem() + $loop->index }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-800">
                                        {{ $employee->name }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        NIK: {{ $employee->no_induk_karyawan ?: '-' }}
                                        · PIN: {{ $employee->fingerprint_pin ?: '-' }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $employee->bagian ?: '-' }}
                                        · {{ $employee->penempatan ?: '-' }}
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    @if($employee->bpjs_tk_terdaftar)
                                        <span class="font-medium text-green-700">Terdaftar</span>
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $employee->program_bpjs_tk ?: '-' }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">Belum terdaftar</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if($employee->bpjs_kesehatan_terdaftar)
                                        <span class="font-medium text-green-700">Terdaftar</span>
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $employee->kelas_bpjs_kesehatan ?: '-' }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">Belum terdaftar</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <form id="bpjs-form-{{ $employee->id }}"
                                          action="{{ route('master-bpjs.update-tarif', $employee->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="flex items-center gap-2">
                                            <span class="text-slate-400">Rp</span>
                                            <input type="number"
                                                   name="tarif_bpjs"
                                                   min="0"
                                                   max="9999999999999.99"
                                                   step="0.01"
                                                   value="{{ old('tarif_bpjs', $employee->tarif_bpjs) }}"
                                                   placeholder="0"
                                                   class="w-40 rounded-lg border-slate-200 text-sm focus:border-slate-400 focus:ring-0">
                                        </div>
                                    </form>
                                </td>

                                <td class="px-4 py-3">
                                    <button type="submit"
                                            form="bpjs-form-{{ $employee->id }}"
                                            class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">
                                        Simpan Tarif
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-slate-400">
                                    Data karyawan tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-4 py-3">
                {{ $employees->links() }}
            </div>
        </div>

        <p class="mt-3 text-xs text-slate-500">
            Tarif di halaman ini adalah satu nominal gabungan per karyawan.
            Mengubah tarif master tidak otomatis mengubah potongan pada periode gaji yang sudah tersimpan.
        </p>
    </div>
</div>
@endsection
