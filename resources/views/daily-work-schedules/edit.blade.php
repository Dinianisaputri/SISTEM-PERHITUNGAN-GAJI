
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Jadwal Kerja Harian
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow">
                <form action="{{ route('daily-work-schedules.update', $dailySchedule) }}"
                      method="POST">
                    @method('PUT')

                    @include('daily-work-schedules._form', [
                        'dailySchedule' => $dailySchedule
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
