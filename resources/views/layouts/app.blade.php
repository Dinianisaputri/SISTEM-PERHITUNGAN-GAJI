<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', config('app.name', 'Perhitungan Gaji'))
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    @auth

        {{-- NAVBAR --}}
        @include('layouts.topbar')

        {{-- CONTENT --}}
        <main class="mx-auto min-h-[calc(100vh-72px)] w-full max-w-[1600px] overflow-x-hidden px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            @isset($header)
                <header class="mb-6">
                    {{ $header }}
                </header>
            @endisset

            @yield('content', $slot ?? '')
        </main>

    @else

        <main class="min-h-screen">
            @yield('content')
        </main>

    @endauth

</body>
</html>