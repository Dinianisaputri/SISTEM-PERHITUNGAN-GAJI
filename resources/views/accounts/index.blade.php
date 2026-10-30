@extends('layouts.app')

@section('title', 'Data Akun')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="soft-card p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="mini-tag">Data Akun</span>

                <h1 class="mt-3 text-3xl font-bold text-slate-800">
                    Kelola Data Akun
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola data akun dan informasi login yang digunakan oleh perusahaan.
                </p>
            </div>

            <a href="{{ route('accounts.create') }}" class="primary-btn">
                + Tambah Akun
            </a>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Card --}}
    <div class="soft-card overflow-hidden">

        {{-- Card Header --}}
        <div class="border-b border-slate-200 px-5 py-4">
            <div>
                <h2 class="text-base font-semibold text-slate-800">
                    Daftar Akun
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Data akun yang tersimpan dalam sistem.
                </p>
            </div>
        </div>

        @if($accounts->count() > 0)

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-slate-600">
                                    No
                                </th>

                                <th class="px-6 py-3 text-left font-semibold text-slate-600">
                                    Nama Akun
                                </th>

                                <th class="px-6 py-3 text-left font-semibold text-slate-600">
                                    Email
                                </th>

                                <th class="px-6 py-3 text-left font-semibold text-slate-600">
                                    Username
                                </th>

                                <th class="px-6 py-3 text-left font-semibold text-slate-600">
                                    Password
                                </th>

                                <th class="px-6 py-3 text-left font-semibold text-slate-600">
                                    Keterangan
                                </th>

                                <th class="px-6 py-3 text-center font-semibold text-slate-600">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                    <tbody class="divide-y divide-slate-100 text-slate-700">

                            @foreach($accounts as $account)

                                <tr class="transition hover:bg-slate-50">

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-4 font-medium text-slate-800">
                                        {{ $account->nama_akun }}
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $account->email ?: '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $account->username ?: '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
    @if($account->password)

        <div class="flex items-center gap-2">

            <input
                type="password"
                value="{{ $account->password }}"
                readonly
                class="w-32 border-0 bg-transparent p-0 text-sm text-slate-600 focus:ring-0"
                id="password-{{ $account->id }}"
            >

            <button
                type="button"
                onclick="toggleAccountPassword({{ $account->id }})"
                class="text-slate-400 hover:text-slate-600 transition"
                title="Tampilkan password"
            >
                {{-- Eye --}}
                <svg id="eye-open-{{ $account->id }}"
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

                {{-- Eye Off --}}
                <svg id="eye-closed-{{ $account->id }}"
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

    @else
        -
    @endif
</td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $account->keterangan ?: '-' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            <a href="{{ route('accounts.edit', $account) }}"
                                               class="rounded-md bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">
                                                Edit
                                            </a>

                                            <form action="{{ route('accounts.destroy', $account) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus akun ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>
                </div>

        @else

            {{-- Empty State --}}
            <div class="px-6 py-16 text-center">

                    <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center">

                        <svg class="w-7 h-7 text-slate-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>

                        </svg>

                    </div>

                    <h3 class="text-base font-semibold text-slate-700">
                        Belum ada data akun
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Belum ada akun yang tersimpan di dalam sistem.
                    </p>

                    <a href="{{ route('accounts.create') }}"
                       class="primary-btn mt-5">

                        <span class="text-lg leading-none">+</span>
                        Tambah Akun

                    </a>

                </div>

        @endif

    </div>
</div>

@endsection
<script>
    function toggleAccountPassword(id) {
        const password = document.getElementById('password-' + id);
        const eyeOpen = document.getElementById('eye-open-' + id);
        const eyeClosed = document.getElementById('eye-closed-' + id);

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