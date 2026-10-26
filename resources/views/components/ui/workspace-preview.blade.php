@props(['variant' => 'default'])
<div {{ $attributes->class(['fm-product-preview']) }} data-variant="{{ $variant }}" aria-label="Ilustrasi antarmuka Flowmerce Studio">
    <div class="fm-product-preview__chrome">
        <span class="fm-product-preview__traffic" aria-hidden="true"><i></i><i></i><i></i></span>
        <span class="fm-product-preview__address">flowmerce / workspace</span>
        <span class="fm-product-preview__chrome-icon material-symbols-outlined" aria-hidden="true">more_horiz</span>
    </div>
    <div class="fm-product-preview__body">
        <div class="fm-product-preview__rail" aria-hidden="true">
            <span class="fm-product-preview__rail-logo">f.</span>
            <span class="material-symbols-outlined is-active">space_dashboard</span>
            <span class="material-symbols-outlined">inventory_2</span>
            <span class="material-symbols-outlined">account_balance_wallet</span>
            <span class="material-symbols-outlined">point_of_sale</span>
            <span class="material-symbols-outlined">settings</span>
        </div>
        <div class="fm-product-preview__content">
            <div class="fm-product-preview__topline">
                <span>Dashboard <span aria-hidden="true">/</span> Overview</span>
                <span class="fm-product-preview__avatar" aria-hidden="true"></span>
            </div>
            <div class="fm-product-preview__welcome">
                <span>Workspace bisnis</span>
                <strong>Semua lebih tertata.</strong>
            </div>
            <div class="fm-product-preview__tiles" aria-hidden="true">
                <div><span class="material-symbols-outlined">payments</span><b>Pendapatan</b><i></i><small></small></div>
                <div><span class="material-symbols-outlined">inventory_2</span><b>Inventaris</b><i></i><small></small></div>
                <div><span class="material-symbols-outlined">monitoring</span><b>Laporan</b><i></i><small></small></div>
            </div>
            <div class="fm-product-preview__chart">
                <div class="fm-product-preview__chart-head"><div><span>Analisis aktivitas</span><strong>Performa usaha</strong></div><span class="fm-product-preview__range">7 hari</span></div>
                <svg viewBox="0 0 460 124" class="fm-product-preview__svg" role="presentation" aria-hidden="true">
                    <defs>
                        <linearGradient id="fm-product-preview-fill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="var(--fm-accent)" stop-opacity=".21"/><stop offset="1" stop-color="var(--fm-accent)" stop-opacity="0"/></linearGradient>
                    </defs>
                    <path d="M 0 28 H 460 M 0 65 H 460 M 0 102 H 460" stroke="var(--fm-border)" stroke-dasharray="4 5" fill="none"/>
                    <path d="M 0 102 C 48 93, 56 54, 110 66 S 191 98, 228 48 S 316 68, 362 32 S 424 37, 460 11 L 460 124 L 0 124 Z" fill="url(#fm-product-preview-fill)"/>
                    <path d="M 0 102 C 48 93, 56 54, 110 66 S 191 98, 228 48 S 316 68, 362 32 S 424 37, 460 11" stroke="var(--fm-accent)" stroke-width="3.5" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>
    <p class="fm-product-preview__caption">Ilustrasi antarmuka · Bukan data transaksi nyata</p>
</div>