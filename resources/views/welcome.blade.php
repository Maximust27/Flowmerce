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
        
        <!-- AOS Animation Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

        <style>
            .orb-1 { filter: blur(120px); opacity: 0.3; }
            .orb-2 { filter: blur(140px); opacity: 0.2; }
            .orb-3 { filter: blur(100px); opacity: 0.2; }
        </style>
    </head>
    <body class="font-body antialiased bg-surface-container-lowest text-on-surface overflow-x-hidden relative">

        {{-- Top Navigation --}}
        <header class="fixed w-full top-0 z-50 bg-surface-container-lowest/80 backdrop-blur-xl border-b border-white/5" data-aos="fade-down" data-aos-duration="600">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-on-primary shadow-lg ai-glow-emerald">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">hub</span>
                    </div>
                    <span class="text-2xl font-bold tracking-tighter text-white">Flowmerce</span>
                </div>
                
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#tentang" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">Tentang</a>
                    <a href="#fitur" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">Fitur</a>
                    <a href="#testimoni" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">Testimoni</a>
                </nav>

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
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary orb-1 rounded-full translate-x-1/2 -translate-y-1/4 animate-float" style="animation-duration: 8s;"></div>
            <div class="absolute top-1/4 left-0 w-[500px] h-[500px] bg-violet-600 orb-2 rounded-full -translate-x-1/2 animate-float" style="animation-duration: 7s; animation-direction: reverse;"></div>
            <div class="absolute bottom-0 left-1/2 w-[800px] h-[400px] bg-sky-500 orb-3 rounded-full -translate-x-1/2 translate-y-1/2 animate-float" style="animation-duration: 10s;"></div>

            <div class="relative max-w-7xl mx-auto px-6 text-center z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card border border-white/10 mb-8" data-aos="fade-up">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-primary">Revolusi UMKM #1 di Indonesia</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold tracking-tighter text-white mb-8 leading-tight" data-aos="fade-up" data-aos-delay="100">
                    Sistem Manajemen<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-sky-400 to-violet-500">Berbasis AI</span> Terintegrasi
                </h1>
                
                <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-12 leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                    Tinggalkan pencatatan manual. Flowmerce menganalisis stok, melacak keuangan, dan bertindak sebagai konsultan bisnis pintar Anda 24/7.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4" data-aos="fade-up" data-aos-delay="300">
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-surface-container-lowest font-bold rounded-2xl text-lg hover:scale-[1.03] active:scale-95 transition-all flex items-center justify-center gap-3 shadow-[0_0_40px_rgba(255,255,255,0.3)]">
                        Mulai Sekarang
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                    @endif
                    <a href="#fitur" class="w-full sm:w-auto px-8 py-4 glass-card text-white font-bold rounded-2xl text-lg hover:bg-white/10 hover:scale-[1.03] active:scale-95 transition-all flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">play_circle</span>
                        Lihat Demo
                    </a>
                </div>

                {{-- Dashboard Preview Mockup --}}
                <div class="mt-24 relative max-w-5xl mx-auto" data-aos="fade-up" data-aos-delay="500">
                    <div class="rounded-3xl glass-card-strong p-2 shadow-[0_30px_100px_-15px_rgba(16,185,129,0.3)] border border-white/10 hover:-translate-y-2 transition-transform duration-500">
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

        {{-- About Section --}}
        <section id="tentang" class="py-24 bg-surface-container-lowest border-y border-white/5 relative overflow-hidden">
            <div class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-violet-600/10 rounded-full blur-[120px] -translate-y-1/2"></div>
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div data-aos="fade-right">
                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-white mb-6 border border-white/10">
                            <span class="material-symbols-outlined text-3xl">psychology</span>
                        </div>
                        <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-6 leading-tight">
                            Bukan Sekadar Pembukuan, <span class="text-violet-400">Ini Otak Bisnis Anda.</span>
                        </h2>
                        <p class="text-lg text-slate-400 leading-relaxed mb-6">
                            Masalah terbesar UMKM adalah tidak tahu produk mana yang paling mendatangkan cuan dan kapan stok akan habis. Kami mengubah data mentah Anda menjadi wawasan praktis.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-slate-300 font-medium">
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                Setup dalam hitungan menit, bukan berhari-hari.
                            </li>
                            <li class="flex items-center gap-3 text-slate-300 font-medium">
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                Chat dengan AI untuk konsultasi seperti dengan manusia.
                            </li>
                            <li class="flex items-center gap-3 text-slate-300 font-medium">
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                Server cloud sekelas enterprise yang 100% aman.
                            </li>
                        </ul>
                    </div>
                    <div class="relative" data-aos="fade-left" data-aos-delay="200">
                        <div class="glass-card inner-glow p-6 rounded-3xl ai-glow-violet bg-surface-container relative z-10 scale-[0.9] lg:scale-100 hover:rotate-2 transition-transform duration-500">
                             <div class="flex flex-col gap-4">
                                 <div class="glass-card-strong p-4 rounded-xl flex items-center gap-4">
                                     <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined">trending_up</span>
                                     </div>
                                     <div>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Growth</p>
                                        <h4 class="text-xl font-bold font-jb text-white">+125%</h4>
                                     </div>
                                 </div>
                                 <div class="glass-card-strong p-4 rounded-xl flex items-center gap-4 border border-violet-500/30">
                                     <div class="w-12 h-12 bg-violet-500/20 rounded-full flex items-center justify-center text-violet-400">
                                        <span class="material-symbols-outlined">smart_toy</span>
                                     </div>
                                     <div>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Saran AI</p>
                                        <h4 class="text-sm font-bold text-white">Naikkan harga Indomie 5%.</h4>
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
            <div class="absolute w-full h-[1px] bg-gradient-to-r from-transparent via-primary/50 to-transparent top-0"></div>
            <div class="max-w-7xl mx-auto px-6">
                <div class="mb-16 md:text-center" data-aos="fade-up">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-4">Fitur Eksekutif</h2>
                    <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto">Kami merancang Flowmerce dengan alat skala enterprise, disederhanakan khusus untuk UMKM.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Feat 1 --}}
                    <div class="glass-card inner-glow p-8 rounded-3xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/20 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-14 h-14 rounded-2xl bg-primary/20 flex items-center justify-center text-primary mb-6 border border-primary/30">
                            <span class="material-symbols-outlined text-3xl">precision_manufacturing</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">AI Prediksi Stok</h3>
                        <p class="text-slate-400 leading-relaxed">
                            Tidak perlu menebak kapan harus restock. AI kami menganalisis pola penjualan Anda secara matematis dan memberikan notifikasi sebelum barang habis total.
                        </p>
                    </div>

                    {{-- Feat 2 --}}
                    <div class="glass-card inner-glow p-8 rounded-3xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-sky-500/20 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-14 h-14 rounded-2xl bg-sky-500/20 flex items-center justify-center text-sky-400 mb-6 border border-sky-500/30">
                            <span class="material-symbols-outlined text-3xl">account_balance</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Keuangan Otomatis</h3>
                        <p class="text-slate-400 leading-relaxed">
                            Setiap ada penjualan, sistem langsung mencatat Laba Rugi real-time. Ekspor ke PDF dengan satu klik untuk keperluan laporan atau investor.
                        </p>
                    </div>

                    {{-- Feat 3 --}}
                    <div class="glass-card inner-glow p-8 rounded-3xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-violet-500/20 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-14 h-14 rounded-2xl bg-violet-500/20 flex items-center justify-center text-violet-400 mb-6 border border-violet-500/30">
                            <span class="material-symbols-outlined text-3xl">chat_bubble</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Konsultan Chat AI</h3>
                        <p class="text-slate-400 leading-relaxed">
                            Jangan ambil keputusan membabi-buta. Tanya AI tentang performa toko Anda. AI Flowmerce bisa menjawab dengan menampilkan data nyata dan wawasan spesifik.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Testimonials Section --}}
        <section id="testimoni" class="py-24 bg-surface-container-lowest relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-4">Dipercaya oleh Ribuan UMKM</h2>
                    <p class="text-slate-400 text-lg">Membantu bisnis lokal berkembang lebih pintar dan cepat.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="glass-card p-8 rounded-3xl flex flex-col gap-6" data-aos="zoom-in" data-aos-delay="100">
                        <div class="flex items-center gap-1 text-amber-400">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <p class="text-lg text-slate-300 font-medium italic">"Dulu saya sering kehabisan stok barang laku karena pencatatan manual di buku. Sejak pakai Flowmerce, asisten AI-nya ngingetin saya H-3 sebelum stok habis. Omzet warung naik 30%!"</p>
                        <div class="mt-auto flex items-center gap-4 border-t border-white/10 pt-6">
                            <div class="w-12 h-12 bg-surface-container rounded-full flex items-center justify-center text-xl font-bold text-primary">A</div>
                            <div>
                                <p class="text-white font-bold">Ahmad Suryani</p>
                                <p class="text-xs text-slate-500 uppercase tracking-widest">Warung Kelontong Berkah</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card p-8 rounded-3xl flex flex-col gap-6" data-aos="zoom-in" data-aos-delay="200">
                        <div class="flex items-center gap-1 text-amber-400">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <p class="text-lg text-slate-300 font-medium italic">"Fitur Laba Rugi-nya magis. Tiap malam saya cukup buka P&L Export, semua angka akurat. Dulu repot ngitung nota pakai kalkulator berjam-jam."</p>
                        <div class="mt-auto flex items-center gap-4 border-t border-white/10 pt-6">
                            <div class="w-12 h-12 bg-surface-container rounded-full flex items-center justify-center text-xl font-bold text-sky-400">R</div>
                            <div>
                                <p class="text-white font-bold">Rina Mustika</p>
                                <p class="text-xs text-slate-500 uppercase tracking-widest">Toko Baju Fashion 99</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Call To Action --}}
        <section class="py-32 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/20 via-surface-container-lowest to-violet-600/20 opacity-50"></div>
            <div class="absolute w-full h-[1px] bg-gradient-to-r from-transparent via-primary/30 to-transparent top-0"></div>
            <div class="absolute w-full h-[1px] bg-gradient-to-r from-transparent via-violet-500/30 to-transparent bottom-0"></div>
            <div class="max-w-4xl mx-auto px-6 text-center relative z-10" data-aos="zoom-out-up">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-widest mb-6">
                    Mulai dalam 60 detik
                </span>
                <h2 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6">
                    Siap Membawa UMKM Anda <br>Tumbuh Eksponensial?
                </h2>
                <p class="text-xl text-slate-400 mb-10 max-w-2xl mx-auto">
                    Bergabung dengan ekosistem digital cerdas Flowmerce dan ubah data toko Anda menjadi rahasia kesuksesan.
                </p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex px-10 py-5 bg-gradient-to-r from-primary to-sky-400 text-on-primary-container font-extrabold rounded-2xl text-xl hover:scale-105 hover:shadow-[0_0_60px_rgba(78,222,163,0.4)] active:scale-95 transition-all">
                        Buat Akun Gratis Sekarang
                    </a>
                @endif
            </div>
        </section>

        {{-- Footer --}}
        <footer class="pt-16 pb-8 bg-surface-container-lowest border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">hub</span>
                            <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400 tracking-tighter">Flowmerce</span>
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed max-w-sm">
                            Platform asisten manajemen UMKM yang ditenagai oleh kecerdasan buatan, dirancang untuk memudahkan manajemen operasional toko kelontong di seluruh Nusantara.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-4 uppercase tracking-widest text-xs">Produk</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-sm text-slate-500 hover:text-white transition-colors">Toko Digital</a></li>
                            <li><a href="#" class="text-sm text-slate-500 hover:text-white transition-colors">Kasir POS</a></li>
                            <li><a href="#" class="text-sm text-slate-500 hover:text-white transition-colors">Ai Flowmerce</a></li>
                            <li><a href="#" class="text-sm text-slate-500 hover:text-white transition-colors">Harga</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-4 uppercase tracking-widest text-xs">Perusahaan</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-sm text-slate-500 hover:text-white transition-colors">Tentang Kami</a></li>
                            <li><a href="#" class="text-sm text-slate-500 hover:text-white transition-colors">Hubungi Kami</a></li>
                            <li><a href="#" class="text-sm text-slate-500 hover:text-white transition-colors">Kebijakan Privasi</a></li>
                            <li><a href="#" class="text-sm text-slate-500 hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-8 border-t border-white/5">
                    <p class="text-sm text-slate-500 font-medium">
                        &copy; {{ date('Y') }} Flowmerce AI Technologies. Hak Cipta Dilindungi.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-colors"><span class="material-symbols-outlined text-sm">language</span></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-colors"><span class="material-symbols-outlined text-sm">mail</span></a>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                AOS.init({
                    once: true,
                    offset: 50,
                    duration: 600,
                    easing: 'ease-out-cubic'
                });
            });
        </script>
    </body>
</html>
