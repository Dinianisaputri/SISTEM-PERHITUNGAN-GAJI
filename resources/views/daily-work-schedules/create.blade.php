
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Jadwal Kerja Harian
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow">
                <form action="{{ route('daily-work-schedules.store') }}" method="POST">
                    @include('daily-work-schedules._form', [
                        'dailySchedule' => null
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
