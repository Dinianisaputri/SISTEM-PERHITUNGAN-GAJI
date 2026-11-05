
@csrf

<div class="space-y-4">
    <div>
        <label for="kode" class="mb-1 block text-sm font-medium">
            Kode Jadwal
        </label>
        <input id="kode" name="kode" type="text"
               value="{{ old('kode', $workSchedule?->kode) }}"
               placeholder="Contoh: S1"
               required maxlength="255"
               class="w-full rounded-lg border-gray-300">
        @error('kode')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nama" class="mb-1 block text-sm font-medium">
            Nama Jadwal
        </label>
        <input id="nama" name="nama" type="text"
               value="{{ old('nama', $workSchedule?->nama) }}"
               placeholder="Contoh: Shift Pagi"
               required maxlength="255"
               class="w-full rounded-lg border-gray-300">
        @error('nama')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="jam_masuk" class="mb-1 block text-sm font-medium">
                Jam Masuk
            </label>
            <input id="jam_masuk" name="jam_masuk" type="time"
                   value="{{ old('jam_masuk', $workSchedule ? substr($workSchedule->jam_masuk, 0, 5) : '') }}"
                   required class="w-full rounded-lg border-gray-300">
            @error('jam_masuk')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="jam_pulang" class="mb-1 block text-sm font-medium">
                Jam Pulang
            </label>
            <input id="jam_pulang" name="jam_pulang" type="time"
                   value="{{ old('jam_pulang', $workSchedule ? substr($workSchedule->jam_pulang, 0, 5) : '') }}"
                   required class="w-full rounded-lg border-gray-300">
            @error('jam_pulang')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="keterangan" class="mb-1 block text-sm font-medium">
            Keterangan
        </label>
        <textarea id="keterangan" name="keterangan" rows="3"
                  class="w-full rounded-lg border-gray-300"
                  placeholder="Opsional">{{ old('keterangan', $workSchedule?->keterangan) }}</textarea>
        @error('keterangan')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
            Simpan
        </button>

        <a href="{{ route('work-schedules.index') }}"
           class="rounded-lg border border-gray-300 px-4 py-2 hover:bg-gray-50">
            Batal
        </a>
    </div>
</div>
