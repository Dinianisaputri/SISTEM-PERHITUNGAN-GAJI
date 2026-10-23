<nav class="w-full border-b border-slate-200 bg-white shadow-sm">
    <div class="mx-auto flex min-h-[72px] max-w-7xl items-center justify-between px-6">

        {{-- LOGO / NAMA SISTEM --}}
        <div class="flex items-center">
            <a
                href="{{ route('dashboard') }}"
                class="text-lg font-bold text-blue-700"
            >
                SISTEM?
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

                <div class="absolute left-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-2 shadow-lg">

                    <a
                        href="{{ route('employees.dashboard') }}"
                        class="block rounded-lg px-4 py-2.5 text-sm transition
                        {{ request()->routeIs('employees.dashboard')
                            ? 'bg-blue-50 font-medium text-blue-700'
                            : 'text-slate-700 hover:bg-slate-50' }}"
                    >
                        Dashboard
                    </a>

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


            {{-- GAJI KARYAWAN --}}
            <details class="relative group">
    <summary class="flex cursor-pointer list-none items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
        <span>Gaji Karyawan</span>

        <svg
            class="h-4 w-4 transition group-open:rotate-180"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
            />
        </svg>
    </summary>

    <div class="absolute left-0 z-50 mt-2 w-52 overflow-hidden rounded-2xl border border-slate-200 bg-white py-2 shadow-lg">

        {{-- Dashboard --}}
        <a
            href="{{ route('salary.dashboard') }}"
            class="block px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50 hover:text-blue-600"
        >
            Dashboard
        </a>

        {{-- Gaji Borongan --}}
        <a
            href="{{ route('checklogs.index') }}"
            class="block px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50 hover:text-blue-600"
        >
            Gaji Borongan
        </a>

        {{-- Gaji Harian --}}
        <a
            href="{{ route('daily_salaries.index') }}"
            class="block px-4 py-3 text-sm text-slate-400 transition"
        >
            Gaji Harian
        </a>

        {{-- Gaji Bulanan --}}
        <a
            href="#"
            class="block px-4 py-3 text-sm text-slate-400 transition"
        >
            Gaji Bulanan
        </a>

        {{-- Arsip Gaji --}}
        <a
            href="{{ route('salary_archives.index') }}"
            class="block px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50 hover:text-blue-600"
        >
            Arsip Gaji
        </a>

        {{-- Data Fingerprint --}}
        <a
            href="{{ route('attendance.index') }}"
            class="block px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50 hover:text-blue-600"
        >
            Data Fingerprint
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

                <div class="absolute left-0 top-full z-50 mt-2 w-48 rounded-xl border border-slate-200 bg-white p-2 shadow-lg">

                    <a
                        href="{{ route('regulations.dashboard') }}"
                        class="block rounded-lg px-4 py-2.5 text-sm transition
                        {{ request()->routeIs('regulations.dashboard')
                            ? 'bg-blue-50 font-medium text-blue-700'
                            : 'text-slate-700 hover:bg-slate-50' }}"
                    >
                        Dashboard
                    </a>

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