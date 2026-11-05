@extends('layouts.app')

@section('title', 'Pengaturan Tarif Gaji')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <p class="text-sm font-medium text-emerald-600">
            PENGGAJIAN
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-800">
            Pengaturan Tarif Gaji
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Kelola tarif untuk perhitungan gaji karyawan.
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Periksa kembali input tarif:</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('salary-settings.update') }}"
          method="POST"
          class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-800">
                Tarif Berdasarkan Jenis Karyawan
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Tarif harian berlaku umum untuk karyawan harian. Tarif karyawan bulanan diatur per orang pada bagian berikut.
            </p>

            <div class="mt-5 grid gap-5 md:grid-cols-2">
                @foreach ([
                    'borongan_per_jam' => 'Tarif Borongan per Jam',
                    'harian_per_hari' => 'Tarif Harian per Hari',
                ] as $key => $label)
                    <div>
                        <label for="{{ $key }}"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            {{ $label }}
                        </label>

                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-500">Rp</span>
                            <input
                                id="{{ $key }}"
                                type="number"
                                name="{{ $key }}"
                                min="0"
                                max="999999999"
                                step="1"
                                required
                                value="{{ old($key, $settings[$key]->amount) }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                            >
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-800">
                Tarif Lembur dan Uang Makan
            </h2>

            <div class="mt-5 grid gap-5 md:grid-cols-2">
                @foreach ([
                    'lembur_biasa_per_jam' => 'Lembur Biasa per Jam',
                    'lembur_libur_per_jam' => 'Lembur Hari Libur per Jam',
                    'uang_makan_per_3_jam' => 'Uang Makan per Hari Lembur yang Dihitung (Maks. 1x/Hari)',
                ] as $key => $label)
                    <div>
                        <label for="{{ $key }}"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            {{ $label }}
                        </label>

                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-500">Rp</span>
                            <input
                                id="{{ $key }}"
                                type="number"
                                name="{{ $key }}"
                                min="0"
                                max="999999999"
                                step="1"
                                required
                                value="{{ old($key, $settings[$key]->amount) }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                            >
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex flex-wrap justify-end gap-3">
            <a href="{{ route('salary.index') }}"
               class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Kembali
            </a>

            <button type="submit"
                    class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                Simpan Pengaturan
            </button>
        </div>
    </form>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('salary-settings.index') }}"
              method="GET"
              class="mb-5 grid gap-3 rounded-xl bg-slate-50 p-4 sm:grid-cols-[minmax(0,1fr)_minmax(0,0.7fr)_auto_auto] sm:items-end">
            <div>
                <label for="monthly-employee-search" class="mb-1 block text-sm font-medium text-slate-700">
                    Cari nama karyawan
                </label>
                <input id="monthly-employee-search"
                       type="search"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Ketik nama..."
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            </div>
            <div>
                <label for="monthly-employee-bagian" class="mb-1 block text-sm font-medium text-slate-700">
                    Bagian
                </label>
                <select id="monthly-employee-bagian"
                        name="bagian"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    <option value="">Semua bagian</option>
                    @foreach ($monthlyBagians as $optionBagian)
                        <option value="{{ $optionBagian }}" @selected($bagian === $optionBagian)>
                            {{ $optionBagian }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="min-h-11 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                Cari
            </button>
            @if ($search !== '' || $bagian !== '')
                <a href="{{ route('salary-settings.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-white">
                    Reset
                </a>
            @endif
        </form>

        <div class="mb-5">
            <h2 class="text-base font-semibold text-slate-800">
                Tarif Harian Karyawan Bulanan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Tambahkan tarif per karyawan dan tanggal mulai berlaku. Perubahan baru tidak menimpa tarif lama, sehingga riwayat dan periode payroll sebelumnya tetap tercatat.
            </p>
        </div>

        @if ($monthlyEmployees->isEmpty())
            <p class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
                @if ($search !== '' || $bagian !== '')
                    Tidak ada karyawan bulanan yang cocok dengan pencarian ini.
                @else
                    Belum ada karyawan bulanan aktif.
                @endif
            </p>
        @else
            <div class="space-y-4">
                @foreach ($monthlyEmployees as $employee)
                    <article class="rounded-xl border border-slate-200 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-slate-800">{{ $employee->name }}</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $employee->bagian ?: 'Bagian belum diisi' }}
                                    <span class="mx-1">·</span>
                                    PIN {{ $employee->fingerprint_pin ?: '-' }}
                                </p>
                            </div>

                            <p class="text-sm text-slate-700">
                                Tarif berlaku:
                                <strong>
                                    @if ($employee->active_salary_rate)
                                        Rp {{ number_format((float) $employee->active_salary_rate->amount, 0, ',', '.') }}
                                        / hari hadir
                                        sejak {{ $employee->active_salary_rate->effective_from->format('d/m/Y') }}
                                    @else
                                        Belum diatur
                                    @endif
                                </strong>
                            </p>
                        </div>

                        <form action="{{ route('salary-settings.monthly-rates.store') }}"
                              method="POST"
                              class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] lg:items-end">
                            @csrf
                            <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="bagian" value="{{ $bagian }}">
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                            <div>
                                <label for="amount-{{ $employee->id }}" class="mb-1 block text-sm font-medium text-slate-700">
                                    Tarif per hari hadir (Rp)
                                </label>
                                <input id="amount-{{ $employee->id }}"
                                       type="number"
                                       name="amount"
                                       min="0"
                                       max="999999999"
                                       step="1"
                                       value="{{ old('employee_id') == $employee->id ? old('amount') : '' }}"
                                       required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                                       placeholder="Contoh: 150000">
                            </div>

                            <div>
                                <label for="effective-from-{{ $employee->id }}" class="mb-1 block text-sm font-medium text-slate-700">
                                    Mulai berlaku
                                </label>
                                <input id="effective-from-{{ $employee->id }}"
                                       type="date"
                                       name="effective_from"
                                       value="{{ old('employee_id') == $employee->id ? old('effective_from') : now()->toDateString() }}"
                                       required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                            </div>

                            <button type="submit"
                                    class="min-h-11 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                                Tambahkan tarif
                            </button>
                        </form>

                        @if ($employee->salaryRates->isNotEmpty())
                            <details class="mt-4 border-t border-slate-100 pt-3">
                                <summary class="cursor-pointer text-sm font-medium text-emerald-700">
                                    Riwayat tarif ({{ $employee->salaryRates->count() }})
                                </summary>
                                <div class="mt-3 overflow-x-auto">
                                    <table class="min-w-full text-left text-sm">
                                        <thead>
                                            <tr class="border-b border-slate-100 text-xs uppercase text-slate-500">
                                                <th class="px-3 py-2">Mulai berlaku</th>
                                                <th class="px-3 py-2">Tarif per hari hadir</th>
                                                <th class="px-3 py-2">Dicatat</th>
                                                <th class="px-3 py-2">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($employee->salaryRates as $rate)
                                                <tr class="border-b border-slate-100 last:border-0">
                                                    <td class="px-3 py-2">{{ $rate->effective_from->format('d/m/Y') }}</td>
                                                    <td class="px-3 py-2">Rp {{ number_format((float) $rate->amount, 0, ',', '.') }}</td>
                                                    <td class="px-3 py-2">{{ $rate->created_at->format('d/m/Y') }}</td>
                                                    <td class="px-3 py-2">
                                                        <form action="{{ route('salary-settings.monthly-rates.destroy', [$employee->id, $rate->id]) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('Hapus tarif Rp {{ number_format((float) $rate->amount, 0, ',', '.') }} yang berlaku sejak {{ $rate->effective_from->format('d/m/Y') }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="search" value="{{ $search }}">
                                                            <input type="hidden" name="bagian" value="{{ $bagian }}">
                                                            <button type="submit"
                                                                    class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-200">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection