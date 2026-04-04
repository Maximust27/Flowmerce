<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Flowmerce') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body antialiased">
        <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
            {{-- Desktop Sidebar --}}
            <aside class="w-[260px] h-screen sticky left-0 top-0 bg-[#0a0e1a] shadow-2xl shadow-black/50 flex-col py-8 px-6 border-r border-white/5 z-30 hidden md:flex">
                @include('partials.sidebar')
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 flex flex-col relative min-w-0">
                @include('partials.topbar')
                <div class="flex-1 overflow-y-auto p-8 no-scrollbar">
                    {{ $slot }}
                </div>
            </main>

            @include('partials.mobile-bottom-nav')
        </div>
    </body>
</html>
