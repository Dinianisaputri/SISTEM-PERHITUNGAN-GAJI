<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center md:text-left">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Reset password</p>
            <h2 class="mt-3 text-3xl font-bold text-slate-900">Lupa password?</h2>
            <p class="mt-2 text-sm text-slate-500">Masukkan email Anda, lalu kami akan kirimkan tautan reset password.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input id="email" class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm transition focus:border-emerald-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100" type="email" name="email" value="{{ old('email') }}" required autofocus />
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                Kirim Link Reset
            </button>
        </form>
    </div>
</x-guest-layout>
