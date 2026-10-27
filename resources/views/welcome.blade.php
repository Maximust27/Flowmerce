<x-ui.html-document title="Workspace modern untuk UMKM" body-class="fm-welcome"
    description="Flowmerce Studio menghubungkan inventaris, kasir, gudang, keuangan, dan tim dalam satu workspace untuk UMKM.">
    <div class="fm-public" x-data="flowmerceShell()">
        <header class="fm-public__header">
            <nav class="fm-public__nav" aria-label="Navigasi halaman utama">
                <x-ui.brand :href="url('/')" class="fm-public__brand" />
                <div class="fm-public__nav-links">
                    <a href="#fitur">Fitur</a>
                    <a href="#workspace">Workspace</a>
                    <a href="#mulai">Mulai sekarang</a>
                </div>
                <div class="fm-public__nav-actions">
                    <x-ui.theme-toggle />
                    @auth
                        <a class="fm-public__login" href="{{ auth()->user()->role === 'cashier' ? route('pos.index') : route('dashboard') }}">Workspace</a>
                        <x-ui.button :href="auth()->user()->role === 'cashier' ? route('pos.index') : route('dashboard')">
                            Buka aplikasi <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                        </x-ui.button>
                    @else
                        <a class="fm-public__login" href="{{ route('login') }}" wire:navigate>Masuk</a>
                        <x-ui.button :href="route('register')">Buat akun <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button>
                    @endauth
                </div>
            </nav>
        </header>

        <main>
            <section class="fm-public-hero" aria-labelledby="fm-public-hero-title">
                <div class="fm-public-hero__copy">
                    <span class="fm-public__eyebrow"><span></span>THE NEW WAY TO RUN YOUR BUSINESS</span>
                    <h1 id="fm-public-hero-title">Semua urusan bisnis.<br><em>Satu workspace.</em></h1>
                    <p>Flowmerce membantu usaha kecil bekerja dengan lebih terstruktur—mulai dari inventaris dan kasir hingga keuangan dan pengelolaan tim.</p>
                    <div class="fm-public-hero__actions">
                        @auth
                            <x-ui.button :href="auth()->user()->role === 'cashier' ? route('pos.index') : route('dashboard')">
                                Kembali ke workspace <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                            </x-ui.button>
                        @else
                            <x-ui.button :href="route('register')">Mulai dengan Flowmerce <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button>
                        @endauth
                        <a class="fm-public-hero__secondary" href="#fitur">Jelajahi fitur <span class="material-symbols-outlined" aria-hidden="true">arrow_downward</span></a>
                    </div>
                    <div class="fm-public-hero__trust">
                        <span class="material-symbols-outlined" aria-hidden="true">check_circle</span>
                        Tanpa perlu berpindah-pindah aplikasi untuk pekerjaan utama usaha.
                    </div>
                </div>
                <div class="fm-public-hero__visual">
                    <span class="fm-public-hero__decor fm-public-hero__decor--top" aria-hidden="true"></span>
                    <x-ui.workspace-preview variant="hero" />
                    <span class="fm-public-hero__decor fm-public-hero__decor--bottom" aria-hidden="true"></span>
                </div>
            </section>

            <section class="fm-public-strip" aria-label="Area kerja yang tersedia">
                <div class="fm-public-strip__inner">
                    <span class="fm-public-strip__label">SATU APLIKASI UNTUK</span>
                    <div><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span> Inventaris</div>
                    <div><span class="material-symbols-outlined" aria-hidden="true">point_of_sale</span> Kasir POS</div>
                    <div><span class="material-symbols-outlined" aria-hidden="true">account_balance_wallet</span> Keuangan</div>
                    <div><span class="material-symbols-outlined" aria-hidden="true">groups</span> Tim</div>
                    <div><span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span> Asisten AI</div>
                </div>
            </section>

            <section class="fm-public-section" id="fitur" aria-labelledby="fm-public-features-title">
                <div class="fm-public-section__head">
                    <div><p class="fm-public__eyebrow">FITUR YANG SALING TERHUBUNG</p>
                        <h2 id="fm-public-features-title">Ruang kerja untuk<br><em>seluruh operasional.</em></h2>
                    </div>
                    <p>Fokus pada pekerjaan penting, bukan mengurus terlalu banyak sistem terpisah. Semua modul Flowmerce dirancang dengan navigasi yang konsisten.</p>
                </div>

                <div class="fm-public-features">
                    <article class="fm-public-feature fm-public-feature--featured">
                        <span class="fm-public-feature__icon"><span class="material-symbols-outlined" aria-hidden="true">space_dashboard</span></span>
                        <span class="fm-public-feature__number">01 / WORKSPACE</span>
                        <h3>Semua data penting,<br>dalam satu pandangan.</h3>
                        <p>Pantau pendapatan, tren pemasukan, stok menipis, dan transaksi terbaru dari Dashboard bisnis.</p>
                        <span class="fm-public-feature__illustration" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></span>
                    </article>
                    <article class="fm-public-feature">
                        <span class="fm-public-feature__icon"><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span></span>
                        <span class="fm-public-feature__number">02 / INVENTARIS</span>
                        <h3>Stok yang<br>lebih terkendali.</h3>
                        <p>Kelola katalog produk, kategori, harga, dan pergerakan stok melalui Gudang Digital.</p>
                    </article>
                    <article class="fm-public-feature">
                        <span class="fm-public-feature__icon"><span class="material-symbols-outlined" aria-hidden="true">point_of_sale</span></span>
                        <span class="fm-public-feature__number">03 / POINT OF SALE</span>
                        <h3>Kasir yang<br>praktis digunakan.</h3>
                        <p>Pilih produk, atur keranjang, dan selesaikan transaksi penjualan dalam antarmuka POS.</p>
                    </article>
                    <article class="fm-public-feature">
                        <span class="fm-public-feature__icon"><span class="material-symbols-outlined" aria-hidden="true">monitoring</span></span>
                        <span class="fm-public-feature__number">04 / KEUANGAN</span>
                        <h3>Keuangan<br>lebih mudah dibaca.</h3>
                        <p>Lacak pemasukan, pengeluaran, dan ringkasan keuntungan dari transaksi yang tercatat.</p>
                    </article>
                    <article class="fm-public-feature">
                        <span class="fm-public-feature__icon"><span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span></span>
                        <span class="fm-public-feature__number">05 / SMART TOOLS</span>
                        <h3>Tim dan asisten,<br>di tempat yang sama.</h3>
                        <p>Kelola peran pegawai dan diskusikan pertanyaan bisnis lewat Asisten AI Flowmerce.</p>
                    </article>
                </div>
            </section>

            <section class="fm-public-showcase" id="workspace" aria-labelledby="fm-public-showcase-title">
                <div class="fm-public-showcase__content">
                    <p class="fm-public__eyebrow">DESIGNED FOR CLARITY</p>
                    <h2 id="fm-public-showcase-title">Lebih sedikit distraksi.<br>Lebih banyak <em>kendali.</em></h2>
                    <p>Desain tenang, navigasi sederhana, dukungan mobile, dan pilihan tema terang atau gelap untuk kebutuhan kerja sehari-hari.</p>
                    <div class="fm-public-showcase__details">
                        <span><span class="material-symbols-outlined" aria-hidden="true">check_circle</span> Dashboard yang jelas</span>
                        <span><span class="material-symbols-outlined" aria-hidden="true">check_circle</span> Antarmuka responsif</span>
                        <span><span class="material-symbols-outlined" aria-hidden="true">check_circle</span> Komponen yang konsisten</span>
                    </div>
                </div>
                <div class="fm-public-showcase__orbit" aria-hidden="true">
                    <div class="fm-public-showcase__orbit-center"><img class="fm-public-showcase__brand-image" src="{{ asset('images/branding/flowmerce-icon.webp') }}" width="80" height="80" alt=""></div>
                    <span class="material-symbols-outlined fm-public-showcase__orbit-icon fm-public-showcase__orbit-icon--one">inventory_2</span>
                    <span class="material-symbols-outlined fm-public-showcase__orbit-icon fm-public-showcase__orbit-icon--two">point_of_sale</span>
                    <span class="material-symbols-outlined fm-public-showcase__orbit-icon fm-public-showcase__orbit-icon--three">account_balance_wallet</span>
                    <span class="material-symbols-outlined fm-public-showcase__orbit-icon fm-public-showcase__orbit-icon--four">auto_awesome</span>
                </div>
            </section>

            <section class="fm-public-cta" id="mulai" aria-labelledby="fm-public-cta-title">
                <span class="fm-public__eyebrow">SIAP UNTUK MEMULAI?</span>
                <h2 id="fm-public-cta-title">Kerja lebih tertata.<br><em>Mulai dari sini.</em></h2>
                <p>Bangun workspace bisnismu dan kelola aktivitas usaha dalam satu aplikasi.</p>
                @auth
                    <x-ui.button :href="auth()->user()->role === 'cashier' ? route('pos.index') : route('dashboard')">Buka Flowmerce <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button>
                @else
                    <x-ui.button :href="route('register')">Buat akun Flowmerce <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button>
                @endauth
            </section>
        </main>

        <footer class="fm-public-footer">
            <x-ui.brand :href="url('/')" />
            <span>© {{ date('Y') }} Flowmerce. Dibuat untuk UMKM Indonesia.</span>
            <a href="{{ auth()->check() ? (auth()->user()->role === 'cashier' ? route('pos.index') : route('dashboard')) : route('login') }}">
                {{ auth()->check() ? 'Workspace' : 'Masuk' }} <span class="material-symbols-outlined" aria-hidden="true">north_east</span>
            </a>
        </footer>
    </div>
</x-ui.html-document>