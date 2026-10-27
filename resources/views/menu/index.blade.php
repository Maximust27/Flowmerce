<x-ui.html-document :title="'Menu '.$table->table_number.' · '.($owner->business_name ?: 'Flowmerce')" body-class="fm-guest"
    description="Lihat menu dan pesan langsung dari meja melalui Flowmerce.">
    @vite('resources/js/guest-menu.js')
    <main class="fm-guest__app" data-fm-motion-root="guest" data-fm-guest
          data-order-url="{{ route('guest.order.store', ['tableCode' => $tableCode]) }}"
          data-table-number="{{ $table->table_number }}"
          data-qris="{{ $owner->gopay_qr_image ? Storage::url($owner->gopay_qr_image) : '' }}">
        <header class="fm-guest__header">
            <div><x-ui.brand :href="url('/')" /><span class="fm-guest__business">{{ $owner->business_name ?: 'Menu Usaha' }}</span></div>
            <div class="fm-guest__header-actions">
                <span class="fm-guest__table"><span class="material-symbols-outlined" aria-hidden="true">table_restaurant</span>{{ $table->table_number }}</span>
                <div x-data="flowmerceShell()"><x-ui.theme-toggle /></div>
                <button type="button" class="fm-guest__mobile-cart" data-cart-toggle aria-label="Buka keranjang">
                    <span class="material-symbols-outlined" aria-hidden="true">shopping_bag</span>
                    <span data-cart-count>0</span>
                </button>
            </div>
        </header>

        <div class="fm-guest__layout">
            <section class="fm-guest__catalog" aria-labelledby="fm-guest-catalog-title">
                <div class="fm-guest__intro">
                    <p class="fm-section-eyebrow">MENU / {{ $table->table_number }}</p>
                    <h1 id="fm-guest-catalog-title">Pilih menu favoritmu.</h1>
                    <p>Pesan langsung dari meja. Kasir akan memproses pesanan setelah kode ditunjukkan.</p>
                </div>
                <label class="fm-guest__search"><span class="material-symbols-outlined" aria-hidden="true">search</span><input data-search type="search" placeholder="Cari makanan atau minuman..." autocomplete="off"><span class="sr-only">Cari menu</span></label>
                <div class="fm-guest__categories" role="group" aria-label="Kategori menu">
                    @foreach($categories as $category)
                        <button type="button" class="fm-guest__category" data-category="{{ $category }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">{{ $category }}</button>
                    @endforeach
                </div>

                <div class="fm-guest__products" data-product-grid>
                    @forelse($products as $product)
                        <article class="fm-guest__product" data-product-card
                            data-product-id="{{ $product->id }}" data-category-name="{{ $product->category ?: 'Lainnya' }}"
                            data-product-name="{{ $product->name }}">
                            <div class="fm-guest__product-image">
                                <span class="fm-guest__image-placeholder material-symbols-outlined" aria-hidden="true">restaurant</span>
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" loading="lazy">
                                @endif
                            </div>
                            <div class="fm-guest__product-body">
                                <p class="fm-guest__product-category">{{ $product->category ?: 'Lainnya' }}</p>
                                <h2>{{ $product->name }}</h2>
                                <div class="fm-guest__product-footer">
                                    <span>Rp {{ number_format($product->sell_price,0,',','.') }}</span>
                                    <button type="button" data-add-product="{{ $product->id }}" title="Tambah {{ $product->name }}" aria-label="Tambah {{ $product->name }}">
                                        <span class="material-symbols-outlined" aria-hidden="true">add</span>
                                    </button>
                                </div>
                                <p class="fm-guest__stock">Tersedia {{ $product->current_stock }} porsi</p>
                            </div>
                        </article>
                    @empty
                        <x-ui.empty-state icon="restaurant_menu" title="Menu belum tersedia" description="Silakan hubungi staf restoran untuk informasi lebih lanjut." />
                    @endforelse
                </div>
                <p class="fm-guest__no-results" data-no-results hidden>Tidak ada menu yang cocok dengan pencarian.</p>
                <p class="fm-guest__disclaimer">Harga yang ditampilkan adalah harga sebelum pajak 11%. Stok dapat berubah hingga pesanan diproses.</p>
            </section>

            <div class="fm-guest__backdrop" data-cart-backdrop hidden></div>
            <aside class="fm-guest__checkout" data-cart-panel aria-label="Keranjang pesanan">
                <header class="fm-guest__checkout-head">
                    <div><p class="fm-section-eyebrow">YOUR ORDER</p><h2>Keranjang pesanan</h2></div>
                    <button type="button" data-cart-close aria-label="Tutup keranjang"><span class="material-symbols-outlined" aria-hidden="true">close</span></button>
                </header>
                <div class="fm-guest__cart-items" data-cart-items><div class="fm-guest__cart-empty"><span class="material-symbols-outlined" aria-hidden="true">shopping_basket</span><p>Keranjang masih kosong.</p><span>Tambahkan menu untuk mulai memesan.</span></div></div>
                <div class="fm-guest__totals">
                    <div><span>Subtotal</span><strong data-subtotal>Rp 0</strong></div>
                    <div><span>Pajak (11%)</span><strong data-tax>Rp 0</strong></div>
                    <div class="fm-guest__grand-total"><span>Total tagihan</span><strong data-total>Rp 0</strong></div>
                </div>
                <div class="fm-guest__payment">
                    <h3>Metode pembayaran</h3>
                    <label><input type="radio" name="fm-guest-payment" value="CASHIER" checked><span class="material-symbols-outlined" aria-hidden="true">payments</span> Bayar di kasir</label>
                    @if($owner->gopay_qr_image)
                        <label><input type="radio" name="fm-guest-payment" value="QRIS"><span class="material-symbols-outlined" aria-hidden="true">qr_code_2</span> QRIS manual</label>
                        <div class="fm-guest__qris" data-qris-preview hidden>
                            <img src="{{ Storage::url($owner->gopay_qr_image) }}" alt="QRIS usaha {{ $owner->business_name }}" width="180" height="180">
                            <p>Periksa nominal sesuai total. Setelah transfer, pesanan tetap menunggu verifikasi kasir.</p>
                        </div>
                    @endif
                </div>
                <p class="fm-guest__error" data-order-error role="alert" hidden></p>
                <button type="button" class="fm-guest__order-button" data-submit-order disabled>
                    <span class="material-symbols-outlined" aria-hidden="true">receipt_long</span>
                    Kirim pesanan
                </button>
                <p class="fm-guest__checkout-note">Pastikan jumlah dan pilihan produk sudah sesuai.</p>
            </aside>
        </div>

        <div class="fm-guest__confirmation" data-confirmation role="dialog" aria-modal="true" aria-labelledby="fm-order-success-title" hidden>
            <div class="fm-guest__confirmation-card">
                <span class="fm-guest__success-icon"><span class="material-symbols-outlined" aria-hidden="true">check_circle</span></span>
                <p class="fm-section-eyebrow">PESANAN DITERIMA</p>
                <h2 id="fm-order-success-title">Terima kasih, pesananmu tercatat.</h2>
                <p>Tunjukkan kode ini kepada kasir. Pesanan belum dianggap dibayar sampai kasir memverifikasi pembayaran.</p>
                <div class="fm-guest__order-code" data-order-code></div>
                <div class="fm-guest__order-details"><span>Meja: {{ $table->table_number }}</span><strong data-order-total></strong></div>
                <p>Pesanan akan kedaluwarsa apabila tidak diproses dalam 45 menit.</p>
                <button type="button" data-reload-order>Muat ulang menu</button>
            </div>
        </div>

        @php
            $guestProductsJson = json_encode(
                $products->map(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'price' => (float) $product->sell_price,
                    'stock' => (int) $product->current_stock,
                ])->values()->all(),
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            );
        @endphp
        <script id="fm-guest-products" type="application/json">{!! $guestProductsJson !!}</script>
    </main>
</x-ui.html-document>