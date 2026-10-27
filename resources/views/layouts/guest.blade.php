<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Perhitungan Gaji') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

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
