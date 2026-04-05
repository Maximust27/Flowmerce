<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Flowmerce — Sistem Manajemen UMKM Berbasis AI. Kelola toko dan dapatkan saran bisnis AI.">

        <title>{{ config('app.name', 'Flowmerce') }} — {{ $title ?? 'Dashboard' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- AOS Animation Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    </head>
    <body class="font-body antialiased">
        <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

            {{-- ===== SIDEBAR (Desktop) ===== --}}
            <aside class="w-[260px] h-screen sticky left-0 top-0 bg-[#0a0e1a] shadow-2xl shadow-black/50 flex-col py-8 px-6 border-r border-white/5 z-30 hidden md:flex">
                @include('partials.sidebar')
            </aside>

            {{-- ===== SIDEBAR OVERLAY (Mobile) ===== --}}
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 md:hidden"
                 @click="sidebarOpen = false"
                 x-cloak>
            </div>

            {{-- ===== SIDEBAR (Mobile Slide-in) ===== --}}
            <aside x-show="sidebarOpen"
                   x-transition:enter="transition ease-out duration-300 transform"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-200 transform"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full"
                   class="fixed inset-y-0 left-0 w-[260px] bg-[#0a0e1a] shadow-2xl shadow-black/50 flex flex-col py-8 px-6 border-r border-white/5 z-50 md:hidden"
                   x-cloak>
                @include('partials.sidebar')
            </aside>

            {{-- ===== MAIN CONTENT ===== --}}
            <main class="flex-1 flex flex-col relative min-w-0">
                {{-- Top Bar --}}
                @include('partials.topbar')

                {{-- Page Content --}}
                <div class="flex-1 overflow-y-auto p-8 no-scrollbar">
                    {{ $slot }}
                </div>
            </main>

            {{-- ===== MOBILE BOTTOM NAVIGATION ===== --}}
            @include('partials.mobile-bottom-nav')

        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                AOS.init({
                    once: true,
                    easing: 'ease-out-cubic'
                });
            });
        </script>
    </body>
</html>
