
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Kerja Harian
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-gray-600">
                    Atur jadwal kerja berdasarkan bagian dan tanggal.
                </p>

                <a href="{{ route('daily-work-schedules.create') }}"
                   class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    + Tambah Jadwal
                </a>
            </div>

            <form method="GET" action="{{ route('daily-work-schedules.index') }}"
                  class="mb-5 flex flex-wrap items-end gap-3 rounded-lg bg-white p-4 shadow">
                <div>
                    <label for="filter_tanggal" class="mb-1 block text-sm">
                        Tanggal
                    </label>
                    <input id="filter_tanggal" name="tanggal" type="date"
                           value="{{ request('tanggal') }}"
                           class="rounded-lg border-gray-300">
                </div>             
<div>
    <label for="bagian" class="mb-1 block text-sm font-medium text-gray-700">
        Bagian
    </label>

    <select id="bagian" name="bagian" class="w-full rounded-lg border-gray-300">
    <option value="">Semua Bagian</option>

    @foreach ($bagianList as $bagian)
        <option value="{{ $bagian }}"
            @selected(request('bagian') === $bagian)>
            {{ $bagian }}
        </option>
    @endforeach
</select>
    @error('bagian')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


                <button type="submit"
                        class="rounded-lg bg-gray-800 px-4 py-2 text-white">
                    Filter
                </button>

                <a href="{{ route('daily-work-schedules.index') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2">
                    Reset
                </a>
            </form>

            <form method="POST"
                  action="{{ route('daily-work-schedules.bulk-destroy', request()->only(['tanggal', 'bagian'])) }}"
                  data-bulk-delete-form
                  onsubmit="return confirm('Yakin ingin menghapus jadwal yang dipilih?')">
                @csrf
                @method('DELETE')

                <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-gray-600" aria-live="polite">
                        <span data-bulk-selection-count>0</span> jadwal dipilih
                    </p>
                    <button type="submit"
                            data-bulk-delete-button
                            disabled
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50">
                        Hapus yang dipilih
                    </button>
                </div>

                <div class="overflow-x-auto rounded-lg bg-white shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm">
                                    <input type="checkbox"
                                           data-bulk-select-all
                                           aria-label="Pilih semua jadwal di halaman ini"
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                </th>
                                <th class="px-4 py-3 text-left text-sm">Tanggal</th>
                                <th class="px-4 py-3 text-left text-sm">Bagian</th>
                                <th class="px-4 py-3 text-left text-sm">Kode Jadwal</th>
                                <th class="px-4 py-3 text-left text-sm">Jam Kerja</th>
                                <th class="px-4 py-3 text-left text-sm">Keterangan</th>
                                <th class="px-4 py-3 text-left text-sm">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($dailySchedules as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <input type="checkbox"
                                               name="daily_schedule_ids[]"
                                               value="{{ $item->id }}"
                                               data-bulk-select
                                               aria-label="Pilih jadwal {{ $item->tanggal->format('d-m-Y') }} bagian {{ $item->bagian }}"
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $item->tanggal->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-3">{{ $item->bagian ?: 'Belum dipetakan' }}</td>
                                    <td class="px-4 py-3 font-medium">
                                        {{ $item->workSchedule->kode ?? 'Jadwal tidak ditemukan' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($item->workSchedule)
                                            {{ substr($item->workSchedule->jam_masuk, 0, 5) }}
                                            –
                                            {{ substr($item->workSchedule->jam_pulang, 0, 5) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $item->keterangan ?: '-' }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('daily-work-schedules.edit', $item) }}"
                                           class="text-indigo-600 hover:underline">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada jadwal untuk filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            <div class="mt-4">
                {{ $dailySchedules->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
