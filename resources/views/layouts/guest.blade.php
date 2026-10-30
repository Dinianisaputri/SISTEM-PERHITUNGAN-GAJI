<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Perhitungan Gaji') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <script>
            (() => {
                const theme = localStorage.getItem('theme');
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.style.colorScheme = theme === 'dark' ? 'dark' : 'light';
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 font-sans antialiased text-slate-900">
        <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
            <div class="w-full max-w-md">
                <div class="mb-6 flex items-center justify-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-700 text-xs font-bold tracking-wide text-white">PG</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Sistem HRD</p>
                        <p class="text-xs text-slate-500">Starfood International</p>
                    </div>
                </div>
                <section class="rounded-xl border border-slate-200 border-t-2 border-t-emerald-700 bg-white p-6 shadow-sm sm:p-8">
                    {{ $slot }}
                </section>
                <p class="mt-5 text-center text-xs text-slate-400">Sistem pengelolaan karyawan dan penggajian</p>
            </div>
        </main>
    </body>
</html>
