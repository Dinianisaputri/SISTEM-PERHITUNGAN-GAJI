@extends('layouts.app')

@section('title', 'Gaji Karyawan')

@section('content')

<div class="space-y-6">

    
{{-- HEADER --}}
<div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">
            Gaji Karyawan
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Perhitungan gaji berdasarkan data absensi fingerprint.
        </p>
    </div>

    <form action="{{ route('salary.archive') }}" method="POST"
          onsubmit="return confirm('Simpan hasil perhitungan gaji dengan filter saat ini ke arsip?')">
        @csrf

        <input type="hidden" name="tanggal_mulai"
               value="{{ request('tanggal_mulai', $tanggalMulai) }}">

        <input type="hidden" name="tanggal_selesai"
               value="{{ request('tanggal_selesai', $tanggalSelesai) }}">

        <input type="hidden" name="jenis_karyawan"
               value="{{ request('jenis_karyawan') }}">

        <input type="hidden" name="bagian"
               value="{{ request('bagian') }}">

        <input type="hidden" name="search"
               value="{{ request('search') }}">

        <button type="submit"
                class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">
            Simpan Arsip Gaji
        </button>
    </form>
</div>



    {{-- FILTER --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <form
            action="{{ route('salary.index') }}"
            method="GET"
            class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5"
        >

            {{-- TANGGAL MULAI --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="tanggal_mulai"
                    value="{{ request('tanggal_mulai', $tanggalMulai) }}"
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>


            {{-- TANGGAL SELESAI --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Tanggal Selesai
                </label>

                <input
                    type="date"
                    name="tanggal_selesai"
                    value="{{ request('tanggal_selesai', $tanggalSelesai) }}"
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>


            {{-- JENIS KARYAWAN --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Jenis Karyawan
                </label>

                <select
                    name="jenis_karyawan"
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">
                        Semua Jenis
                    </option>

                    @foreach($jenisKaryawanList as $jenis)
                        <option
                            value="{{ $jenis }}"
                            @selected(request('jenis_karyawan') === $jenis)
                        >
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- BAGIAN --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Bagian
                </label>

                <select
                    name="bagian"
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">
                        Semua Bagian
                    </option>

                    @foreach($bagianList as $bagian)
                        <option
                            value="{{ $bagian }}"
                            @selected(request('bagian') === $bagian)
                        >
                            {{ $bagian }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- SEARCH --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    Cari Karyawan
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="PIN / nama / unit penempatan..."
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>


            {{-- BUTTON --}}
            <div class="flex items-end gap-2 lg:col-span-5">

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Terapkan Filter
                </button>

                <a
                    href="{{ route('salary.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    {{-- PERIODE AKTIF --}}
    <div class="rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">

        <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-blue-600">
                    Periode Perhitungan
                </p>

                <p class="mt-1 text-base font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') }}
                    -
                    {{ \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('d F Y') }}
                </p>
            </div>

            <div class="text-sm text-gray-500">
                {{ $salaryEmployees->count() }} karyawan
            </div>

        </div>

    </div>


    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- TABEL --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">

                        <th class="whitespace-nowrap px-4 py-3">
                            No
                        </th>

                        <th class="whitespace-nowrap px-4 py-3">
                            PIN
                        </th>

                        <th class="whitespace-nowrap px-4 py-3">
                            Nama
                        </th>

                        <th class="whitespace-nowrap px-4 py-3">
                            Bagian
                        </th>

                        <th class="whitespace-nowrap px-4 py-3">
                            Jenis
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 text-center">
                            Hari
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 text-center">
                            Jam Kerja
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 text-center">
                            Lembur
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 text-right">
                            Uang Makan
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 text-right">
                            Potongan
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 text-right">
                            Total Gaji
                        </th>
 <th class="whitespace-nowrap px-4 py-3 text-right">
                            BPJS
                        </th>
                        <th class="whitespace-nowrap px-4 py-3 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($salaryEmployees as $index => $data)

                        @php
                            $employee = $data['employee'];

                            $jenis = strtolower(
                                trim($employee->jenis_karyawan ?? '')
                            );
                        @endphp
                            @php
    $jenisBelumDitentukan = $data['jenis_karyawan_kosong'] ?? false;
@endphp
                        <tr class="hover:bg-gray-50">

                            {{-- NO --}}
                            <td class="whitespace-nowrap px-4 py-3 text-gray-500">
                                {{ $index + 1 }}
                            </td>


                            {{-- PIN --}}
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-800">
                                {{ $employee->fingerprint_pin ?: '-' }}
                            </td>


                            {{-- NAMA --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                <div class="font-medium text-gray-800">
                                    {{ $employee->name }}
                                </div>

                            </td>


                            {{-- BAGIAN --}}
                            <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                {{ $employee->bagian ?: '-' }}
                            </td>


                            {{-- JENIS --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                @if($jenis === 'borongan')

                                    <span class="inline-flex rounded-full bg-purple-50 px-2.5 py-1 text-xs font-medium text-purple-700">
                                        {{ $employee->jenis_karyawan }}
                                    </span>

                                @elseif($jenis === 'harian')

                                    <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                        {{ $employee->jenis_karyawan }}
                                    </span>

                                @elseif($jenis === 'bulanan')

                                    <span class="inline-flex rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                        {{ $employee->jenis_karyawan }}
                                    </span>

                                @else

                                    <span class="text-gray-600">
                                        {{ $employee->jenis_karyawan ?: '-' }}
                                    </span>

                                @endif

                            </td>


                            {{-- HARI --}}
<td class="whitespace-nowrap px-4 py-3 text-center text-gray-700">
    @if($jenisBelumDitentukan)
        <span class="text-gray-400">-</span>
    @else
        {{ number_format($data['hari_hadir'], 0, ',', '.') }}
    @endif
</td>


{{-- JAM KERJA --}}
<td class="whitespace-nowrap px-4 py-3 text-center text-gray-700">
    @if($jenisBelumDitentukan)
        <span class="text-gray-400">-</span>
    @else
        {{ number_format($data['jam_kerja'], 2, ',', '.') }}
        jam
    @endif
</td>


{{-- LEMBUR --}}
<td class="whitespace-nowrap px-4 py-3 text-center">
    @if($jenisBelumDitentukan || $jenis === 'borongan')
        <span class="text-gray-400">-</span>
    @else
        <span class="text-gray-700">
            {{ number_format($data['jam_lembur'], 2, ',', '.') }}
            jam
        </span>
    @endif
</td>


{{-- UANG MAKAN --}}
<td class="whitespace-nowrap px-4 py-3 text-right">
    @if($jenisBelumDitentukan || $jenis === 'borongan')
        <span class="text-gray-400">-</span>
    @else
        <span class="text-gray-700">
            Rp {{ number_format($data['uang_makan_lembur'], 0, ',', '.') }}
        </span>
    @endif
</td>


{{-- POTONGAN --}}
<td class="whitespace-nowrap px-4 py-3 text-right text-gray-700">
    @if($jenisBelumDitentukan)
        <span class="text-gray-400">-</span>
    @else
        <span data-salary-deductions>Rp {{ number_format($data['total_potongan'], 0, ',', '.') }}</span>
    @endif
</td>


{{-- TOTAL GAJI --}}
<td class="whitespace-nowrap px-4 py-3 text-right">
    @if($jenisBelumDitentukan)
        <span class="text-xs text-gray-400">Belum ditentukan</span>
    @else
        <div data-salary-net class="font-semibold text-gray-800">
            Rp {{ number_format($data['gaji_bersih'], 0, ',', '.') }}
        </div>

        <div data-salary-gross-label data-salary-gross="{{ $data['gaji_kotor'] }}"
             class="text-xs text-gray-400 {{ $data['total_potongan'] > 0 ? '' : 'hidden' }}">
            Gaji kotor: Rp {{ number_format($data['gaji_kotor'], 0, ',', '.') }}
        </div>
    @endif
</td>

{{-- BPJS --}}
<td class="whitespace-nowrap px-4 py-3 text-center">
    @php
        $salaryDetail = $data['salary_detail'] ?? null;

        $terdaftarBpjs =
            (bool) $employee->bpjs_tk_terdaftar ||
            (bool) $employee->bpjs_kesehatan_terdaftar;

        $tarifBpjs = (float) ($employee->tarif_bpjs ?? 0);

        $potongBpjsAktif = $salaryDetail
            ? $salaryDetail->deductions()
                ->where('nama_potongan', 'Potongan BPJS')
                ->where('keterangan', 'Potongan otomatis dari Master BPJS')
                ->exists()
            : false;
    @endphp

    @if($terdaftarBpjs)
        @if($tarifBpjs > 0)
            <form
                action="{{ route('salary.bpjs.toggle', $employee->id) }}"
                method="POST"
                class="js-bpjs-toggle flex flex-col items-center gap-1"
            >
                @csrf

                <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}">

                <label class="inline-flex cursor-pointer items-center gap-2">
                    <input
                        type="checkbox"
                        name="potong_bpjs"
                        value="1"
                        {{ $potongBpjsAktif ? 'checked' : '' }}
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >

                    <span data-bpjs-status class="text-xs text-gray-600">
                        {{ $potongBpjsAktif ? 'Aktif' : 'Potong' }}
                    </span>
                </label>

                <span class="text-xs text-gray-500">
                    Rp {{ number_format($tarifBpjs, 0, ',', '.') }}
                </span>
            </form>
        @else
            <span class="text-xs text-amber-600">Tarif belum diisi</span>
        @endif
    @else
        <span class="text-xs text-gray-300">-</span>
    @endif
</td>

{{-- AKSI --}}
<td class="whitespace-nowrap px-4 py-3 text-center">
    <a href="{{ route('salary.detail', array_merge(
        ['employee' => $employee->id],
        request()->only([
            'tanggal_mulai',
            'tanggal_selesai',
            'jenis_karyawan',
            'bagian',
            'search'
        ])
    )) }}"
       class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100">
        Detail
    </a>
</td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="12"
                                class="px-6 py-12 text-center"
                            >

                                <div class="text-gray-400">

                                    <svg
                                        class="mx-auto h-10 w-10"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"
                                        />
                                    </svg>

                                    <p class="mt-3 text-sm font-medium text-gray-600">
                                        Data karyawan tidak ditemukan.
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        Coba ubah filter atau kata pencarian.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-bpjs-toggle').forEach(form => {
        const checkbox = form.querySelector('[name="potong_bpjs"]');
        const status = form.querySelector('[data-bpjs-status]');
        if (!checkbox || !status) return;

        checkbox.addEventListener('change', () => form.requestSubmit());
        form.addEventListener('submit', async event => {
            event.preventDefault();
            const previousValue = checkbox.checked;
            const formData = new FormData(form);
            checkbox.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.message || 'Potongan BPJS gagal diperbarui.');
                }
                if (typeof result.active !== 'boolean' || typeof result.total_deductions !== 'number') {
                    throw new Error('Total potongan BPJS tidak dapat dikonfirmasi.');
                }

                checkbox.checked = result.active;
                status.textContent = result.active ? 'Aktif' : 'Potong';

                const row = form.closest('tr');
                const deductions = row?.querySelector('[data-salary-deductions]');
                const netSalary = row?.querySelector('[data-salary-net]');
                const grossLabel = row?.querySelector('[data-salary-gross-label]');
                if (deductions && netSalary && grossLabel) {
                    const gross = Number(grossLabel.dataset.salaryGross);
                    const totalDeductions = result.total_deductions;
                    const formatter = new Intl.NumberFormat('id-ID', {
                        maximumFractionDigits: 0
                    });

                    deductions.textContent = `Rp ${formatter.format(totalDeductions)}`;
                    netSalary.textContent = `Rp ${formatter.format(Math.max(gross - totalDeductions, 0))}`;
                    grossLabel.classList.toggle('hidden', totalDeductions <= 0);
                }
            } catch (error) {
                checkbox.checked = previousValue;
                window.alert(error instanceof Error
                    ? error.message
                    : 'Potongan BPJS gagal diperbarui.');
            } finally {
                checkbox.disabled = false;
            }
        });
    });
});
</script>

@endsection