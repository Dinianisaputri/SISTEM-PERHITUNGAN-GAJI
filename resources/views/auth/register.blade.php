<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center md:text-left">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600">Create account</p>
            <h2 class="mt-3 text-3xl font-bold text-slate-900">Daftar akun</h2>
            <p class="mt-2 text-sm text-slate-500">Buat akun baru untuk mulai mengelola sistem payroll.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nama</label>
                <input id="name" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input id="email" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                <input id="password" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100" type="password" name="password" required autocomplete="new-password" />
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                <input id="password_confirmation" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100" type="password" name="password_confirmation" required autocomplete="new-password" />
                @error('password_confirmation')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between gap-3 pt-2">
                <a class="text-sm font-medium text-blue-600 transition hover:text-blue-700" href="{{ route('login') }}">
                    Sudah punya akun?
                </a>

                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-4 focus:ring-blue-200">
                    Daftar
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
