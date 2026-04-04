<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Flowmerce') }} — Sistem Manajemen UMKM Berbasis AI</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .orb-1 { filter: blur(120px); opacity: 0.3; }
            .orb-2 { filter: blur(140px); opacity: 0.2; }
            .orb-3 { filter: blur(100px); opacity: 0.2; }
        </style>
    </head>
    <body class="font-body antialiased bg-surface-container-lowest text-on-surface overflow-x-hidden relative">

        {{-- Top Navigation --}}
        <header class="fixed w-full top-0 z-50 bg-surface-container-lowest/80 backdrop-blur-xl border-b border-white/5">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-on-primary shadow-lg ai-glow-emerald">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">hub</span>
                    </div>
                    <span class="text-2xl font-bold tracking-tighter text-white">Flowmerce</span>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Ke Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hidden md:block text-sm font-bold text-slate-300 hover:text-white transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary ai-glow-emerald">Coba Gratis</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        {{-- Hero Section --}}
        <section class="relative pt-40 pb-24 lg:pt-48 lg:pb-32 overflow-hidden">
            {{-- Background Effects --}}
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#4edea3 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary orb-1 rounded-full translate-x-1/2 -translate-y-1/4"></div>
            <div class="absolute top-1/4 left-0 w-[500px] h-[500px] bg-violet-600 orb-2 rounded-full -translate-x-1/2"></div>
            <div class="absolute bottom-0 left-1/2 w-[800px] h-[400px] bg-sky-500 orb-3 rounded-full -translate-x-1/2 translate-y-1/2"></div>

            <div class="relative max-w-7xl mx-auto px-6 text-center z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card border border-white/10 mb-8 animate-fade-in-up">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-primary">Revolusi UMKM #1 di Indonesia</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold tracking-tighter text-white mb-8 animate-fade-in-up" style="animation-delay: 100ms;">
                    Sistem Manajemen<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-sky-400 to-violet-500">Berbasis AI</span> Terintegrasi
                </h1>
                
                <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-12 animate-fade-in-up leading-relaxed" style="animation-delay: 200ms;">
                    Tinggalkan pencatatan manual. Flowmerce menganalisis stok, melacak keuangan, dan bertindak sebagai konsultan bisnis pintar Anda 24/7.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up" style="animation-delay: 300ms;">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-surface-container-lowest font-bold rounded-2xl text-lg hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                        Mulai Sekarang
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                    <a href="#fitur" class="w-full sm:w-auto px-8 py-4 glass-card text-white font-bold rounded-2xl text-lg hover:bg-white/10 active:scale-95 transition-all flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">play_circle</span>
                        Lihat Demo
                    </a>
                </div>

                {{-- Dashboard Preview Mockup --}}
                <div class="mt-24 relative max-w-5xl mx-auto animate-fade-in-up" style="animation-delay: 500ms;">
                    <div class="rounded-3xl glass-card-strong p-2 shadow-2xl border border-white/10">
                        <div class="rounded-2xl overflow-hidden bg-surface-container relative aspect-[16/9] border border-white/5">
                            {{-- Fake App Content --}}
                            <div class="absolute top-0 w-full h-12 bg-surface-container-high border-b border-white/5 flex items-center px-4 gap-2">
                                <div class="w-3 h-3 rounded-full bg-error"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-primary"></div>
                            </div>
                            <div class="pt-16 p-6 flex gap-6 h-full">
                                <div class="w-48 hidden md:flex flex-col gap-3">
                                    <div class="h-8 bg-white/5 rounded-lg w-full"></div>
                                    <div class="h-8 bg-white/5 rounded-lg w-full"></div>
                                    <div class="h-8 bg-white/5 rounded-lg w-full"></div>
                                    <div class="h-8 bg-gradient-to-r from-violet-600/20 to-sky-600/20 rounded-lg w-full mt-auto"></div>
                                </div>
                                <div class="flex-1 space-y-6">
                                    <div class="h-24 bg-gradient-to-r from-violet-600/10 to-primary/10 border border-violet-500/20 rounded-xl relative overflow-hidden">
                                        <div class="absolute inset-y-0 left-6 flex items-center gap-4">
                                             <div class="w-10 h-10 rounded-full bg-violet-600"></div>
                                             <div class="space-y-2">
                                                 <div class="h-3 w-48 bg-white/20 rounded"></div>
                                                 <div class="h-2 w-32 bg-white/10 rounded"></div>
                                             </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="h-32 bg-white/5 rounded-xl"></div>
                                        <div class="h-32 bg-white/5 rounded-xl"></div>
                                        <div class="h-32 bg-white/5 rounded-xl"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section id="fitur" class="py-24 bg-surface-container relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="mb-16 md:text-center">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-4">Fitur Unggulan</h2>
                    <p class="text-slate-400 text-lg md:text-xl">Semua yang Anda butuhkan untuk mengembangkan bisnis.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Feat 1 --}}
                    <div class="glass-card inner-glow p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 ai-glow-emerald">
                        <div class="w-14 h-14 rounded-2xl bg-primary/20 flex items-center justify-center text-primary mb-6">
                            <span class="material-symbols-outlined text-3xl">precision_manufacturing</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">AI Prediksi Stok</h3>
                        <p class="text-slate-400 leading-relaxed">
                            Tidak perlu menebak kapan harus restock. AI kami menganalisis pola penjualan Anda dan memberikan notifikasi sebelum barang habis.
                        </p>
                    </div>

                    {{-- Feat 2 --}}
                    <div class="glass-card inner-glow p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 ai-glow-sky">
                        <div class="w-14 h-14 rounded-2xl bg-sky-500/20 flex items-center justify-center text-sky-400 mb-6">
                            <span class="material-symbols-outlined text-3xl">account_balance</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Kasir & Keuangan </h3>
                        <p class="text-slate-400 leading-relaxed">
                            Sistem kasir cerdas yang otomatis membuat Laporan Laba Rugi real-time tiap kali terjadi transaksi. Transparansi finansial penuh.
                        </p>
                    </div>

                    {{-- Feat 3 --}}
                    <div class="glass-card inner-glow p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 ai-glow-violet">
                        <div class="w-14 h-14 rounded-2xl bg-violet-500/20 flex items-center justify-center text-violet-400 mb-6">
                            <span class="material-symbols-outlined text-3xl">chat_bubble</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Asisten AI Chat</h3>
                        <p class="text-slate-400 leading-relaxed">
                            Tanya apapun tentang performa bisnis Anda layaknya chat dengan konsultan. "Apakah hari ini untung?", "Apa barang paling laku?".
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Call To Action --}}
        <section class="py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/10 via-surface-container-lowest to-violet-600/10"></div>
            <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6">
                    Siap Membawa UMKM Anda <br>Tumbuh Eksponensial?
                </h2>
                <p class="text-xl text-slate-400 mb-10">
                    Bergabung dengan ribuan pemilik usaha yang telah bertransformasi ke ekosistem digital cerdas Flowmerce.
                </p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex px-10 py-5 bg-gradient-to-r from-primary to-sky-400 text-on-primary-container font-extrabold rounded-2xl text-xl hover:scale-105 active:scale-95 transition-transform shadow-[0_0_40px_rgba(78,222,163,0.3)]">
                        Buat Akun Gratis Sekarang
                    </a>
                @endif
            </div>
        </section>

        {{-- Footer --}}
        <footer class="border-t border-white/5 py-12 bg-surface-container-lowest">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">hub</span>
                    <span class="font-bold text-white tracking-tighter">Flowmerce</span>
                </div>
                <p class="text-sm text-slate-500 font-medium">
                    &copy; {{ date('Y') }} Flowmerce AI Technologies. All rights reserved.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">share</span></a>
                    <a href="#" class="text-slate-500 hover:text-white transition-colors"><span class="material-symbols-outlined">language</span></a>
                </div>
            </div>
        </footer>

    </body>
</html>
