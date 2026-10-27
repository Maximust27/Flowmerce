<x-ui.html-document :title="'Meja Tidak Tersedia · '.$table->table_number" body-class="fm-guest">
    <div class="fm-guest__inactive">
        <x-ui.brand :href="url('/')" />
        <section class="fm-guest__inactive-card">
            <span class="fm-guest__inactive-icon"><span class="material-symbols-outlined" aria-hidden="true">table_restaurant</span></span>
            <p class="fm-section-eyebrow">SCAN TO ORDER</p>
            <h1>Meja sedang tidak aktif.</h1>
            <p>Menu untuk {{ $table->table_number }} sedang dinonaktifkan. Silakan hubungi kasir atau staf untuk bantuan.</p>
            <button type="button" onclick="window.location.reload()" class="fm-button"><span class="material-symbols-outlined" aria-hidden="true">refresh</span>Coba lagi</button>
        </section>
    </div>
</x-ui.html-document>