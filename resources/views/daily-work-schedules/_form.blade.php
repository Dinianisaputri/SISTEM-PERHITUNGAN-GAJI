@csrf

<div class="space-y-5">
    <div>
        <label for="tanggal" class="block text-sm font-medium text-gray-700">
            Tanggal
        </label>
        <input
            type="date"
            name="tanggal"
            id="tanggal"
            value="{{ old('tanggal', $dailySchedule?->tanggal?->format('Y-m-d')) }}"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error('tanggal')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="bagian" class="block text-sm font-medium text-gray-700">
            Bagian
        </label>
        <select
            name="bagian"
            id="bagian"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="">-- Pilih Bagian --</option>
            @foreach ($bagianList as $bagian)
                <option
                    value="{{ $bagian }}"
                    @selected(old('bagian', $dailySchedule?->bagian) === $bagian)
                >
                    {{ $bagian }}
                </option>
            @endforeach
        </select>
        @error('bagian')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="work_schedule_id" class="block text-sm font-medium text-gray-700">
            Jadwal Kerja
        </label>
        <select
            name="work_schedule_id"
            id="work_schedule_id"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="">-- Pilih Jadwal Kerja --</option>
            @foreach ($workSchedules as $workSchedule)
                <option
                    value="{{ $workSchedule->id }}"
                    @selected((string) old('work_schedule_id', $dailySchedule?->work_schedule_id) === (string) $workSchedule->id)
                >
                    {{ $workSchedule->kode }} - {{ $workSchedule->nama }}
                    ({{ $workSchedule->jam_masuk }} - {{ $workSchedule->jam_pulang }})
                </option>
            @endforeach
        </select>
        @error('work_schedule_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="keterangan" class="block text-sm font-medium text-gray-700">
            Keterangan
        </label>
        <textarea
            name="keterangan"
            id="keterangan"
            rows="3"
            maxlength="255"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >{{ old('keterangan', $dailySchedule?->keterangan) }}</textarea>
        @error('keterangan')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <button
            type="submit"
            class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700"
        >
            Simpan
        </button>

        <a
            href="{{ route('daily-work-schedules.index') }}"
            class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50"
        >
            Batal
        </a>
    </div>
</div>