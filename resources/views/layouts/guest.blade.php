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
    <body class="font-sans antialiased bg-slate-100 text-slate-900">
        <div class="min-h-screen flex items-center justify-center bg-[radial-gradient(circle_at_top,_#eff6ff_0%,_#dbeafe_28%,_#e2e8f0_100%)] p-4 sm:p-6">
            <div class="w-full max-w-4xl overflow-hidden rounded-[28px] border border-blue-100 bg-white shadow-[0_30px_80px_rgba(37,99,235,0.12)]">
                <div class="grid md:grid-cols-[1.05fr_0.95fr]">
                    <div class="hidden md:flex flex-col justify-between bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 p-10 text-white">
                        <div>
                            <div class="mb-8 flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-lg font-bold shadow-lg shadow-blue-900/20 backdrop-blur-sm">
                                    PG
                                </div>
                                <div>
                                    <div class="text-[10px] font-semibold uppercase tracking-[0.25em] text-blue-100">Payroll System</div>
                                    <div class="text-2xl font-bold">Perhitungan Gaji</div>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <p class="text-sm uppercase tracking-[0.3em] text-blue-100/80">Dashboard</p>
                                <h1 class="text-4xl font-bold leading-tight">Kelola penggajian dengan lebih cepat dan rapi.</h1>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm">
                            <p class="text-sm text-blue-100">Semua kebutuhan absensi, payroll, dan rekap gaji dalam satu sistem.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-center p-6 sm:p-10">
                        <div class="w-full max-w-md">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
