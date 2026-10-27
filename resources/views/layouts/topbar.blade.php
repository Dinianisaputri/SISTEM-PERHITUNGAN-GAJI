
<nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex min-h-[68px] max-w-[1600px] items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

        {{-- LOGO --}}
        <a href="{{ route('dashboard') }}" class="flex shrink-0 items-center gap-3" aria-label="Beranda Sistem HRD">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-700 text-xs font-bold tracking-wide text-white">PG</span>
            <span class="hidden sm:block">
                <span class="block text-sm font-semibold leading-5 text-slate-900">Sistem HRD</span>
                <span class="block text-[11px] leading-4 text-slate-500">Starfood International</span>
            </span>
        </a>

        {{-- MENU DESKTOP --}}
        <div class="hidden items-center gap-1 xl:flex">

            {{-- BERANDA --}}
            <a href="{{ route('dashboard') }}"
               class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                Beranda
            </a>

            {{-- KARYAWAN --}}
            <details class="group relative">
                <summary class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('employees.*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    Karyawan
                    <svg class="h-4 w-4 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </summary>
                <div class="absolute left-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                    <a href="{{ route('employees.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('employees.dashboard') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Ringkasan karyawan</a>
                    <a href="{{ route('employees.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('employees.index', 'employees.create', 'employees.edit', 'employees.show') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Data karyawan</a>
                </div>
            </details>

            {{-- ABSENSI --}}
            <details class="group relative">
                <summary class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('attendance.*', 'holidays.*', 'work-schedules.*', 'daily-work-schedules.*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    Absensi
                    <svg class="h-4 w-4 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </summary>
                <div class="absolute left-0 top-full z-50 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                    <a href="{{ route('attendance.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('attendance.index') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Data fingerprint</a>
                    <a href="{{ route('attendance.unregistered') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('attendance.unregistered') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">PIN belum terdaftar</a>
                    <a href="{{ route('work-schedules.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('work-schedules.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Master Jam Kerja</a>
                    <a href="{{ route('daily-work-schedules.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('daily-work-schedules.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Jadwal Kerja Harian</a>
                    <a href="{{ route('holidays.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('holidays.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Hari libur</a>
                </div>
            </details>

            {{-- PENGGAJIAN --}}
            <details class="group relative">
                <summary class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('salary.*', 'salary_archives.*', 'checklogs.*', 'salary-settings.*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    Penggajian
                    <svg class="h-4 w-4 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </summary>
                <div class="absolute left-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                    <a href="{{ route('salary.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('salary.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Gaji karyawan</a>
                    <a href="{{ route('salary_archives.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('salary_archives.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Arsip gaji</a>
                </div>
            </details>

            {{-- REGULASI --}}
            <details class="group relative">
                <summary class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('regulations.*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    Regulasi
                    <svg class="h-4 w-4 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </summary>
                <div class="absolute left-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                    
                    <a href="{{ route('regulations.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('regulations.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Dashboard Regulasi</a>
                    <a href="{{ route('regulations.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('regulations.index') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Data Regulasi</a>
                </div>
            </details>

{{-- SURAT HRD --}}
<details class="group relative">
    <summary class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('warning-letters.*', 'contracts.*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
        Surat
        <svg class="h-4 w-4 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 0 1 1.06 0l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
        </svg>
    </summary>

<div class="absolute left-0 top-full z-50 mt-2 w-60 rounded-xl border border-slate-200 bg-white p-2 shadow-lg">

    <a href="{{ route('warning-notices.index') }}"class="block rounded-lg px-3 py-2.5 text-sm text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700">
        <span class="block font-medium">Surat Teguran</span>
        <span class="mt-1 block text-xs text-slate-500">
            Kelola dan cetak surat teguran
        </span>
    </a>

    <a href="{{ route('warning-letters.index') }}"
       class="mt-1 block rounded-lg px-3 py-2.5 text-sm text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700">
        <span class="block font-medium">Surat Peringatan (SP)</span>
        <span class="mt-1 block text-xs text-slate-500">
            SP I, SP II, dan SP III
        </span>
    </a> 
<a href="{{ route('contracts.index') }}"
   class="block rounded-lg px-3 py-2.5 text-sm text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700">
    <span class="block font-medium">Surat Kontrak</span>
    <span class="mt-1 block text-xs text-slate-500">
        Kelola data kontrak karyawan
    </span>
</a>

</div>
</details>

            {{-- PENGATURAN --}}
            <details class="group relative">
                <summary class="flex cursor-pointer list-none items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('accounts.*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    Pengaturan
                    <svg class="h-4 w-4 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </summary>
                <div class="absolute right-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                    <a href="{{ route('accounts.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('accounts.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Data akun</a>
                     <a href="{{ route('master-data.bagian.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('master-data.bagian.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        Master Bagian
                    </a>
                    <a href="{{ route('master-data.jabatan.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('master-data.jabatan.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        Master Jabatan
                    </a>
                    <a href="{{ route('master-data.penempatan.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('master-data.penempatan.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        Master Penempatan
                    </a>
                    <a href="{{ route('master-data.kategori-regulasi.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('master-data.kategori-regulasi.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        Kategori Regulasi
                    </a>
                    <a href="{{ route('salary-settings.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('salary-settings.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">Pengaturan Gaji</a>
                </div>
            </details>
        </div>

        {{-- PROFIL DAN KELUAR DESKTOP --}}
        <div class="hidden shrink-0 items-center gap-3 xl:flex">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-700">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="max-w-28 truncate">{{ auth()->user()->name }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">Keluar</button>
            </form>
        </div>

        {{-- MENU MOBILE --}}
        <details class="group relative xl:hidden">
            <summary class="flex h-10 cursor-pointer list-none items-center gap-2 rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5.75A.75.75 0 0 1 3.75 5h12.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 5.75Zm0 4.25a.75.75 0 0 1 .75-.75h12.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 10Zm0 4.25a.75.75 0 0 1 .75-.75h12.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/>
                </svg>
                Menu
            </summary>

            <div class="absolute right-0 top-full z-50 mt-2 max-h-[calc(100vh-5rem)] w-[min(20rem,calc(100vw-2rem))] overflow-y-auto rounded-xl border border-slate-200 bg-white p-3 shadow-xl shadow-slate-900/10">

                <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-700 hover:bg-slate-50' }}">Beranda</a>

                <p class="px-3 pb-1 pt-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400">Karyawan</p>
                <a href="{{ route('employees.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Ringkasan karyawan</a>
                <a href="{{ route('employees.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Data karyawan</a>

                <p class="px-3 pb-1 pt-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400">Absensi</p>
                <a href="{{ route('attendance.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Data fingerprint</a>
                <a href="{{ route('attendance.unregistered') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">PIN belum terdaftar</a>
                <a href="{{ route('work-schedules.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Master Jam Kerja</a>
                <a href="{{ route('daily-work-schedules.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Jadwal Kerja Harian</a>
                <a href="{{ route('holidays.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('holidays.*') ? 'bg-emerald-50 font-medium text-emerald-800' : 'text-slate-700 hover:bg-slate-50' }}">Hari libur</a>

                <p class="px-3 pb-1 pt-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400">Penggajian</p>
                <a href="{{ route('salary-settings.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Pengaturan Gaji</a>
                <a href="{{ route('salary.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Gaji karyawan</a>
                <a href="{{ route('salary_archives.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Arsip gaji</a>

                <p class="px-3 pb-1 pt-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400">Regulasi</p>
                <a href="{{ route('regulations.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Dashboard Regulasi</a>
                <a href="{{ route('regulations.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Daftar Regulasi</a>

                <p class="px-3 pb-1 pt-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400">Surat</p>
                <a href="{{ route('warning-notices.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Surat Teguran</a>
                <a href="{{ route('warning-letters.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Surat Pringatan</a>
                <a href="{{ route('contracts.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Surat Kontrak</a>

                <p class="px-3 pb-1 pt-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400">Pengaturan</p>
                <a href="{{ route('accounts.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Data akun</a>
                <a href="{{ route('master-data.bagian.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50"> Master Bagian</a>
                <a href="{{ route('master-data.jabatan.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50"> Master Jabatan</a>
                <a href="{{ route('master-data.penempatan.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50"> Master Penempatan </a>
                <a href="{{ route('master-data.kategori-regulasi.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50"> Kategori Regulasi</a>
                <a href="{{ route('salary-settings.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Pengaturan Gaji</a>
                <div class="mt-2 border-t border-slate-100 pt-2">
                    <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Profil: {{ auth()->user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-red-700 hover:bg-red-50">Keluar</button>
                    </form>
                </div>
            </div>
        </details>
    </div>
</nav>
