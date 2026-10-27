@extends('layouts.app')

@section('title', 'Tambah Akun')

@section('content')

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-3xl px-6">

            {{-- HEADER --}}
            <div class="mb-6">
                <a
                    href="{{ route('accounts.index') }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-700"
                >
                    ← Kembali ke Data Akun
                </a>

                <h1 class="mt-4 text-2xl font-bold text-slate-800">
                    Tambah Data Akun
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Tambahkan akun yang digunakan dalam pengelolaan sistem.
                </p>
            </div>


            {{-- VALIDATION ERROR --}}
            @if($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                    <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- FORM --}}
            <form
                method="POST"
                action="{{ route('accounts.store') }}"
                class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            >
                @csrf

                <div class="space-y-5">

                    {{-- NAMA AKUN --}}
                    <div>
                        <label
                            for="nama_akun"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Nama Akun <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="nama_akun"
                            name="nama_akun"
                            value="{{ old('nama_akun') }}"
                            required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Contoh: SIMPEL PPMA"
                        >
                    </div>


                    {{-- EMAIL --}}
                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="nama@email.com"
                        >
                    </div>


                    {{-- USERNAME --}}
                    <div>
                        <label
                            for="username"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Username
                        </label>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Username akun"
                        >
                    </div>


                    {{-- PASSWORD --}}
                    <div>
                        <label
                            for="password"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Password
                        </label>

                        <input
                            type="text"
                            id="password"
                            name="password"
                            value="{{ old('password') }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Password akun"
                        >

                        <p class="mt-1.5 text-xs text-slate-400">
                            Password akan disimpan secara terenkripsi.
                        </p>
                    </div>


                    {{-- KETERANGAN --}}
                    <div>
                        <label
                            for="keterangan"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Keterangan
                        </label>

                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="4"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Keterangan tambahan..."
                        >{{ old('keterangan') }}</textarea>
                    </div>

                </div>


                {{-- ACTION --}}
                <div class="mt-7 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">

                    <a
                        href="{{ route('accounts.index') }}"
                        class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700"
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection