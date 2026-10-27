<nav class="w-full border-b border-slate-200 bg-white shadow-sm">
    <div class="mx-auto flex min-h-[72px] max-w-7xl items-center justify-between px-6">

        {{-- LOGO / NAMA SISTEM --}}
        <div class="flex items-center">
            <a
                href="{{ route('dashboard') }}"
                class="text-lg font-bold text-blue-700"
            >
                SISTEM HRD
            </a>
        </div>


        {{-- MENU --}}
        <div class="flex items-center gap-2">

            {{-- BERANDA --}}
            <a
                href="{{ route('dashboard') }}"
                class="rounded-lg px-4 py-2.5 text-sm font-medium transition
                {{ request()->routeIs('dashboard')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-700 hover:bg-slate-50 hover:text-blue-700' }}"
            >
                Beranda
            </a>


            {{-- DATA KARYAWAN --}}
            <details class="group relative">
                <summary
                    class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-blue-700"
                >
                    Data Karyawan

                    <svg
                        class="h-4 w-4 transition-transform group-open:rotate-180"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </summary>

                <div
                    class="absolute left-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-2 shadow-lg"
                >

                    {{-- Dashboard Karyawan --}}
                    <a
                        href="{{ route('employees.dashboard') }}"
                        class="block rounded-lg px-4 py-2.5 text-sm transition
                        {{ request()->routeIs('employees.dashboard')
                            ? 'bg-blue-50 font-medium text-blue-700'
                            : 'text-slate-700 hover:bg-slate-50' }}"
                    >
                        Dashboard
                    </a>

                    {{-- Data Karyawan --}}
                    <a
                        href="{{ route('employees.index') }}"
                        class="block rounded-lg px-4 py-2.5 text-sm transition
                        {{ request()->routeIs('employees.index')
                            ? 'bg-blue-50 font-medium text-blue-700'
                            : 'text-slate-700 hover:bg-slate-50' }}"
                    >
                        Data Karyawan
                    </a>

                </div>
            </details>


            {{-- DATA ABSENSI --}}
            <details class="group relative">
                <summary
                    class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-blue-700"
                >
                    Data Absensi

                    <svg
                        class="h-4 w-4 transition-transform group-open:rotate-180"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </summary>

                <div
                    class="absolute left-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-2 shadow-lg"
                >

                    {{-- Dashboard Absensi --}}
                    <a
                        href="{{ route('attendance.index') }}"
                        class="block rounded-lg px-4 py-2.5 text-sm transition
                        {{ request()->routeIs('attendance.index')
                            ? 'bg-blue-50 font-medium text-blue-700'
                            : 'text-slate-700 hover:bg-slate-50' }}"
                    >
                        Dashboard Absensi
                    </a>

                    {{-- Data Absensi --}}
                    <a
                        href="{{ route('attendance.index') }}"
                        class="block rounded-lg px-4 py-2.5 text-sm transition
                        {{ request()->routeIs('attendance.index')
                            ? 'bg-blue-50 font-medium text-blue-700'
                            : 'text-slate-700 hover:bg-slate-50' }}"
                    >
                        Data Absensi
                    </a>

                    {{-- PIN Belum Terdaftar --}}
                    <a
                        href="{{ route('attendance.unregistered') }}"
                        class="block rounded-lg px-4 py-2.5 text-sm transition
                        {{ request()->routeIs('attendance.unregistered')
                            ? 'bg-blue-50 font-medium text-blue-700'
                            : 'text-slate-700 hover:bg-slate-50' }}"
                    >
                        PIN Belum Terdaftar
                    </a>

                </div>
            </details>


            {{-- REGULASI --}}
            <details class="group relative">
                <summary
                    class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-blue-700"
                >
                    Regulasi

                    <svg
                        class="h-4 w-4 transition-transform group-open:rotate-180"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </summary>

                <div
                    class="absolute left-0 top-full z-50 mt-2 w-48 rounded-xl border border-slate-200 bg-white p-2 shadow-lg"
                >

                    {{-- Dashboard Regulasi --}}
                    <a
                        href="{{ route('regulations.dashboard') }}"
                        class="block rounded-lg px-4 py-2.5 text-sm transition
                        {{ request()->routeIs('regulations.dashboard')
                            ? 'bg-blue-50 font-medium text-blue-700'
                            : 'text-slate-700 hover:bg-slate-50' }}"
                    >
                        Dashboard
                    </a>

                    {{-- Data Regulasi --}}
                    <a
                        href="{{ route('regulations.index') }}"
                        class="block rounded-lg px-4 py-2.5 text-sm transition
                        {{ request()->routeIs('regulations.index')
                            ? 'bg-blue-50 font-medium text-blue-700'
                            : 'text-slate-700 hover:bg-slate-50' }}"
                    >
                        Data Regulasi
                    </a>

                </div>

            </details>

            {{-- SURAT HRD --}}
            <details class="group relative">
                <summary
                    class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-blue-700"
                >
                    Surat HRD

                    <svg
                        class="h-4 w-4 transition-transform group-open:rotate-180"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </summary>

                <div
                    class="absolute left-0 top-full z-50 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-2 shadow-lg"
                >
                    <div class="block rounded-lg px-4 py-2.5 text-sm text-slate-400">
                        Surat Peringatan
                        <span class="block text-xs">Segera dibuat</span>
                    </div>

                    <div class="block rounded-lg px-4 py-2.5 text-sm text-slate-400">
                        Surat Kontrak
                        <span class="block text-xs">Segera dibuat</span>
                    </div>
                </div>
            </details>

            <a
                href="{{ route('salary.index') }}"
                class="px-4 py-2 text-sm font-medium transition
                    {{ request()->routeIs('salary.*')
                        ? 'text-slate-900'
                        : 'text-slate-600 hover:text-slate-900' }}"
            >
                Gaji Karyawan
            </a>

            {{-- DATA AKUN --}}
            <a
                href="{{ route('accounts.index') }}"
                class="rounded-lg px-4 py-2.5 text-sm font-medium transition
                {{ request()->routeIs('accounts.*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-700 hover:bg-slate-50 hover:text-blue-700' }}"
            >
                Data Akun
            </a>


            {{-- LOGOUT --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
                class="ml-2"
            >
                @csrf

                <button
                    type="submit"
                    class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-red-50 hover:text-red-600"
                >
                    Logout
                </button>
            </form>

        </div>
    </div>
</nav>