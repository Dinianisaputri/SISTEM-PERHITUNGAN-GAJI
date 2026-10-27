@extends('layouts.app')

@section('title', 'Edit Data Akun')

@section('content')

<div class="min-h-screen bg-slate-50 -mx-4 -my-6 sm:-mx-6 lg:-mx-8">
    <div class="max-w-4xl mx-auto px-6 py-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">
                Edit Data Akun
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Perbarui informasi akun.
            </p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">

            <form action="{{ route('accounts.update', $account) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">

                    {{-- Nama Akun --}}
                    <div>
                        <label for="nama_akun"
                               class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Akun <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama_akun"
                            id="nama_akun"
                            value="{{ old('nama_akun', $account->nama_akun) }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >

                        @error('nama_akun')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email"
                               class="block text-sm font-semibold text-slate-700 mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $account->email) }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username"
                               class="block text-sm font-semibold text-slate-700 mb-2">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            id="username"
                            value="{{ old('username', $account->username) }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('username')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password"
                               class="block text-sm font-semibold text-slate-700 mb-2">
                            Password
                        </label>

                        <div class="relative">

                            <input
                                type="password"
                                name="password"
                                id="password"
                                value="{{ old('password', $account->password) }}"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 pr-12 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >

                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-slate-600"
                                title="Tampilkan password"
                            >

                                {{-- Eye Open --}}
                                <svg id="eye-open"
                                     xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

                                    <circle cx="12" cy="12" r="3"/>

                                </svg>

                                {{-- Eye Closed --}}
                                <svg id="eye-closed"
                                     xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5 hidden"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M3 3l18 18"/>

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M10.584 10.587a2 2 0 002.828 2.828"/>

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M9.88 5.09A9.77 9.77 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.77 9.77 0 01-4.132 5.04"/>

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M6.228 6.228A9.77 9.77 0 002.458 12C3.732 16.057 7.523 19 12 19a9.77 9.77 0 004.132-.96"/>

                                </svg>

                            </button>

                        </div>

                        @error('password')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label for="keterangan"
                               class="block text-sm font-semibold text-slate-700 mb-2">
                            Keterangan
                        </label>

                        <textarea
                            name="keterangan"
                            id="keterangan"
                            rows="4"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('keterangan', $account->keterangan) }}</textarea>

                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">

                    <a href="{{ route('accounts.index') }}"
                       class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-100 transition">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

@endsection

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        if (password.type === 'password') {
            password.type = 'text';

            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            password.type = 'password';

            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>