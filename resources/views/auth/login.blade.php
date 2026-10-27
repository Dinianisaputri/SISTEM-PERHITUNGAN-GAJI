<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center md:text-left">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Sistem HRD</p>
            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Masuk ke akun</h2>
            <p class="mt-2 text-sm text-slate-500">Silakan login untuk mengakses dashboard gaji karyawan.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input id="email"
                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm transition focus:border-emerald-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username" />
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                <input id="password"
                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm transition focus:border-emerald-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password" />
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between gap-3 pt-1">
                <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-emerald-700 shadow-sm focus:ring-emerald-600" name="remember">
                    <span>Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-emerald-700 transition hover:text-emerald-800" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                Masuk
            </button>
        </form>
    </div>
</x-guest-layout>
