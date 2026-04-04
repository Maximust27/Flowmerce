<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Flowmerce') }} — {{ $title ?? 'Masuk' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            {{-- Left Panel: Branding (hidden on mobile) --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-[var(--color-bg-primary)]">
                {{-- Decorative Orbs --}}
                <div class="orb-emerald" style="top: 20%; left: 10%;"></div>
                <div class="orb-violet" style="bottom: 10%; right: 5%;"></div>

                <div class="relative z-10 flex flex-col items-center justify-center w-full px-12">
                    {{-- Logo --}}
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-3xl font-bold text-gradient-emerald">Flowmerce</span>
                    </div>

                    <h2 class="text-2xl font-bold text-[var(--color-text-primary)] text-center mb-3">
                        Kelola Bisnis Anda<br>dengan Kecerdasan AI
                    </h2>
                    <p class="text-[var(--color-text-secondary)] text-center max-w-md leading-relaxed">
                        Pantau stok, kelola keuangan, dan dapatkan saran bisnis cerdas — semua dalam satu aplikasi yang dibangun khusus untuk UMKM Indonesia.
                    </p>

                    {{-- Feature pills --}}
                    <div class="flex flex-wrap gap-3 mt-8 justify-center">
                        <span class="px-4 py-2 rounded-full glass text-sm text-[var(--color-text-secondary)]">📦 Inventaris Real-time</span>
                        <span class="px-4 py-2 rounded-full glass text-sm text-[var(--color-text-secondary)]">🤖 Konsultan AI 24/7</span>
                        <span class="px-4 py-2 rounded-full glass text-sm text-[var(--color-text-secondary)]">💰 Laporan Keuangan</span>
                    </div>
                </div>
            </div>

            {{-- Right Panel: Auth Form --}}
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-6 py-12 bg-[var(--color-bg-secondary)] relative">
                {{-- Mobile Logo --}}
                <div class="lg:hidden mb-8">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gradient-emerald">Flowmerce</span>
                    </a>
                </div>

                {{-- Form Container --}}
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <p class="mt-8 text-xs text-[var(--color-text-muted)]">
                    &copy; {{ date('Y') }} Flowmerce. Dibangun untuk UMKM Indonesia.
                </p>
            </div>
        </div>
    </body>
</html>
