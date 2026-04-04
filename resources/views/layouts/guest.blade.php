<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Flowmerce — Masuk atau daftar untuk mulai mengelola bisnis Anda dengan AI.">

        <title>{{ config('app.name', 'Flowmerce') }} — {{ $title ?? 'Selamat Datang' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .orb-glow { filter: blur(80px); opacity: 0.4; }
        </style>
    </head>
    <body class="font-body antialiased overflow-hidden">
        <main class="flex min-h-screen w-full">

            {{-- Left Panel: Branding & Decorative --}}
            <section class="hidden lg:flex flex-1 relative items-center justify-center bg-gradient-to-br from-surface-container-lowest to-[#1e1b4b] overflow-hidden">
                {{-- Decorative Orbs --}}
                <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-primary rounded-full orb-glow"></div>
                <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-primary-container rounded-full orb-glow"></div>

                <div class="relative z-10 text-center px-12">
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-5xl text-primary" style="font-variation-settings: 'FILL' 1;">hub</span>
                        <h1 class="text-6xl font-extrabold tracking-tighter text-on-surface">Flowmerce</h1>
                    </div>
                    <p class="text-2xl font-medium text-on-surface-variant max-w-md mx-auto leading-relaxed">
                        Kelola Toko Anda. <span class="text-primary">Dapatkan Saran AI.</span>
                    </p>

                    {{-- Feature Cards --}}
                    <div class="mt-16 grid grid-cols-2 gap-4">
                        <div class="p-6 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm text-left">
                            <span class="material-symbols-outlined text-primary mb-3">auto_awesome</span>
                            <p class="text-sm font-semibold mb-1">AI Insights</p>
                            <p class="text-xs text-on-surface-variant">Prediksi stok otomatis dengan akurasi 98%.</p>
                        </div>
                        <div class="p-6 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm text-left">
                            <span class="material-symbols-outlined text-secondary mb-3">account_balance_wallet</span>
                            <p class="text-sm font-semibold mb-1">Smart Finance</p>
                            <p class="text-xs text-on-surface-variant">Laporan keuangan real-time setiap transaksi.</p>
                        </div>
                    </div>
                </div>

                {{-- Grid Pattern --}}
                <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#4edea3 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>
            </section>

            {{-- Right Panel: Auth Form --}}
            <section class="flex-1 flex items-center justify-center p-6 md:p-12 bg-surface-container-lowest relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-[100px]"></div>

                <div class="w-full max-w-md glass-card p-8 md:p-10 rounded-2xl shadow-2xl relative z-10">
                    {{ $slot }}
                </div>

                {{-- Footer Mobile --}}
                <div class="absolute bottom-6 text-center w-full lg:hidden">
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Powered by Flowmerce AI Intelligence</p>
                </div>
            </section>
        </main>
    </body>
</html>
