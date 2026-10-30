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
                Tarif bulanan tetap diinput manual dan tidak diubah dari halaman ini.
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
                    'uang_makan_per_3_jam' => 'Uang Makan per 3 Jam Lembur',
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
        BPJS Ketenagakerjaan
    </h2>

    <p class="mt-1 text-sm text-slate-500">
        Atur satu nominal potongan BPJS yang digunakan saat HRD
        mencentang BPJS pada detail gaji karyawan.
    </p>

    <div class="mt-5 max-w-md">
        <label for="bpjs_ketenagakerjaan"
               class="mb-2 block text-sm font-medium text-slate-700">
            Nominal Potongan BPJS per Karyawan per Periode
        </label>

        <div class="flex items-center gap-3">
            <span class="text-sm text-slate-500">Rp</span>

            <input
                id="bpjs_ketenagakerjaan"
                type="number"
                name="bpjs_ketenagakerjaan"
                min="0"
                max="999999999"
                step="1"
                required
                value="{{ old(
                    'bpjs_ketenagakerjaan',
                    $settings['bpjs_ketenagakerjaan']->amount
                ) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            >
        </div>
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
</div>
@endsection