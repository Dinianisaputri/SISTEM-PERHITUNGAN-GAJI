
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Master Jam Kerja
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <p class="text-sm text-gray-600">
                    Kelola kode dan jam kerja yang digunakan dalam penjadwalan.
                </p>

                <a href="{{ route('work-schedules.create') }}"
                   class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    + Tambah Jam Kerja
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg bg-white shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm">Kode</th>
                            <th class="px-4 py-3 text-left text-sm">Nama Jadwal</th>
                            <th class="px-4 py-3 text-left text-sm">Jam Masuk</th>
                            <th class="px-4 py-3 text-left text-sm">Jam Pulang</th>
                            <th class="px-4 py-3 text-left text-sm">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($workSchedules as $schedule)
                            <tr>
                                <td class="px-4 py-3 font-medium">
                                    {{ $schedule->kode }}
                                </td>
                                <td class="px-4 py-3">{{ $schedule->nama }}</td>
                                <td class="px-4 py-3">
                                    {{ substr($schedule->jam_masuk, 0, 5) }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ substr($schedule->jam_pulang, 0, 5) }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('work-schedules.edit', $schedule) }}"
                                           class="text-indigo-600 hover:underline">
                                            Edit
                                        </a>

                                        <form action="{{ route('work-schedules.destroy', $schedule) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus jam kerja ini? Jadwal yang masih digunakan tidak dapat dihapus.')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-600 hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada data jam kerja.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $workSchedules->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
