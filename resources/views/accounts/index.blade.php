@extends('layouts.app')

@section('title', 'Data Akun')

@section('content')

<div class="min-h-screen bg-slate-50 -mx-4 -my-6 sm:-mx-6 lg:-mx-8">
    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Data Akun
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola data akun dan informasi login yang digunakan oleh perusahaan.
                </p>
            </div>

            <a href="{{ route('accounts.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                <span class="text-lg leading-none">+</span>
                Tambah Akun
            </a>
        </div>

        {{-- Success --}}
        @if(session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Card --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800">
                    Daftar Akun
                </h2>
            </div>

            @if($accounts->count() > 0)

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 border-b border-slate-200">
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

                        <tbody class="divide-y divide-slate-100">

                            @foreach($accounts as $account)

                                <tr class="hover:bg-slate-50 transition">

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
                                               class="px-3 py-1.5 rounded-md bg-blue-50 text-blue-600 text-xs font-semibold hover:bg-blue-100">
                                                Edit
                                            </a>

                                            <form action="{{ route('accounts.destroy', $account) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus akun ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded-md bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100">
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
                       class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">

                        <span class="text-lg leading-none">+</span>
                        Tambah Akun

                    </a>

                </div>

            @endif

        </div>

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