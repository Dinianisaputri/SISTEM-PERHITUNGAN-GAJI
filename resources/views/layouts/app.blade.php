<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <main class="app-content mx-auto min-h-[calc(100vh-72px)] w-full max-w-[1600px] overflow-x-hidden px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
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