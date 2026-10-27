@props([])
@php
    $pageLabel = match (true) {
        request()->routeIs('register') => 'REGISTRASI',
        request()->routeIs('login') => 'MASUK',
        request()->routeIs('password.request', 'password.reset') => 'PEMULIHAN AKUN',
        request()->routeIs('verification.notice') => 'VERIFIKASI EMAIL',
        request()->routeIs('password.confirm') => 'KONFIRMASI AKUN',
        default => 'AKUN',
    };
@endphp
<div class="fm-auth-layout" data-fm-motion-root="auth" x-data="flowmerceShell()">
    <aside class="fm-auth-aside" aria-label="Tentang Flowmerce">
        <div class="fm-auth-aside__top">
            <x-ui.brand :href="url('/')" class="fm-auth-aside__brand" />
            <span class="fm-auth-aside__edition">STUDIO / 2026</span>
        </div>
        <div class="fm-auth-aside__center">
            <span class="fm-auth-aside__eyebrow"><span></span>ONE WORKSPACE. BETTER BUSINESS.</span>
            <h2 class="fm-auth-aside__headline">Bisnis terasa<br>lebih <em>ringan.</em></h2>
            <p class="fm-auth-aside__copy">Inventaris, penjualan, keuangan, dan tim. Semua tersusun rapi dalam satu ruang kerja yang fokus pada hal penting.</p>
            <x-ui.workspace-preview variant="auth" />
        </div>
        <div class="fm-auth-aside__footer">
            <span>Dibuat untuk UMKM Indonesia</span>
            <span class="fm-auth-aside__footer-star material-symbols-outlined" aria-hidden="true">asterisk</span>
            <span>Flowmerce Workspace</span>
        </div>
    </aside>
    <main class="fm-auth-main" id="main-content">
        <div class="fm-auth-main__top">
            <x-ui.brand :href="url('/')" class="fm-auth-main__brand" />
            <div class="fm-auth-main__tools">
                <a href="{{ url('/') }}" class="fm-auth-main__back"><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span><span>Beranda</span></a>
                <x-ui.theme-toggle />
            </div>
        </div>
        <div class="fm-auth-main__content">
            <section class="fm-auth-panel {{ request()->routeIs('register') ? 'fm-auth-panel--wide' : '' }}" aria-label="{{ $pageLabel }}">
                <div class="fm-auth-panel__context"><span>FLOWMERCE / ACCOUNT</span><span>{{ $pageLabel }}</span></div>
                {{ $slot }}
            </section>
        </div>
        <footer class="fm-auth-main__footer"><span>© {{ date('Y') }} Flowmerce</span><span>Bangun bisnis dengan lebih terarah.</span></footer>
    </main>
</div>