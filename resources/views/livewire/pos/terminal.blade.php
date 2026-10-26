<div class="fm-pos" x-data="{ cartOpen: false }" @keydown.escape.window="cartOpen=false">
  <header class="fm-pos__header">
    <div class="fm-pos__brand"><x-ui.brand :href="auth()->user()->role === 'admin' ? route('dashboard') : route('pos.index')" /><span class="fm-muted" style="font-size:12px;font-weight:600">/ POS</span></div>
    <div class="fm-pos__search">
      <span class="material-symbols-outlined" aria-hidden="true">search</span>
      <input type="search" wire:model.live.debounce.300ms="search" aria-label="Cari produk" placeholder="Cari nama produk…">
    </div>
    <div class="fm-pos__header-actions">
      <button type="button" class="fm-button fm-pos__mobile-cart" data-variant="secondary" @click="cartOpen=true" aria-label="Buka keranjang">
        <span class="material-symbols-outlined" aria-hidden="true">shopping_bag</span>Keranjang ({{ count($cart) }})
      </button>
      @if(auth()->user()->role === 'admin')<a class="fm-button" data-variant="secondary" href="{{ route('dashboard') }}"><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span><span class="hidden sm:inline">Dashboard</span></a>@endif
      <span class="fm-pos__user">{{ auth()->user()->name }}</span>
      <div x-data="flowmerceShell()"><x-ui.theme-toggle /></div>
      <form action="{{ route('logout') }}" method="POST">@csrf<button class="fm-icon-button" type="submit" aria-label="Keluar" title="Keluar"><span class="material-symbols-outlined" aria-hidden="true">logout</span></button></form>
    </div>
  </header>

  <div class="fm-pos__layout">
    <main class="fm-pos__catalog" id="pos-catalog">
      <div class="fm-pos__intro"><div><p class="fm-section-eyebrow">Point of Sale</p><h1 class="fm-pos__title">Kasir & Penjualan</h1><p class="fm-pos__subtitle">Pilih produk untuk menambahkan ke keranjang transaksi.</p></div><x-ui.badge tone="accent">Order #{{ $orderNumber }}</x-ui.badge></div>
      <div class="fm-pos__categories" role="group" aria-label="Filter kategori produk">
        @foreach($categories as $cat)
          <button type="button" class="fm-pill" wire:click="setCategory(@js($cat))" aria-pressed="{{ $activeCategory === $cat ? 'true' : 'false' }}">{{ $cat }}</button>
        @endforeach
      </div>
      <div class="fm-pos__grid">
        @forelse($products as $product)
          <button type="button" class="fm-product" wire:key="pos-product-{{ $product->id }}" wire:click="addToCart({{ $product->id }})" @disabled($product->current_stock <= 0) aria-label="Tambahkan {{ $product->name }} ke keranjang">
            <div class="fm-product__image">
              @if($product->image)<img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" loading="lazy">
              @else<span class="material-symbols-outlined" aria-hidden="true" style="font-size:42px">inventory_2</span>@endif
              <span class="fm-product__stock" data-low="{{ $product->current_stock <= $product->min_stock_alert ? 'true' : 'false' }}">{{ $product->current_stock <= 0 ? 'Habis' : $product->current_stock.' tersedia' }}</span>
            </div>
            <div class="fm-product__body"><p class="fm-product__name">{{ $product->name }}</p><p class="fm-product__category">{{ $product->category ?: 'Tanpa kategori' }}</p>
              <div class="fm-product__footer"><span class="fm-product__price">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span><span class="fm-product__add"><span class="material-symbols-outlined" aria-hidden="true">add</span></span></div>
            </div>
          </button>
        @empty
          <div style="grid-column:1/-1"><x-ui.empty-state icon="inventory_2" title="Produk tidak ditemukan" description="Coba kategori lain atau tambahkan produk baru melalui Inventaris." /></div>
        @endforelse
      </div>
    </main>

    <aside class="fm-pos__summary" :class="{ 'is-open': cartOpen }" aria-label="Ringkasan keranjang">
      <div class="fm-pos__summary-head"><div><h2 class="fm-pos__summary-title">Keranjang Belanja</h2><p class="fm-pos__summary-id">Nomor pesanan: {{ $orderNumber }}</p></div><div class="flex gap-2">
        <button type="button" class="fm-icon-button" wire:click="clearCart" aria-label="Kosongkan keranjang" title="Kosongkan keranjang" @disabled(empty($cart))><span class="material-symbols-outlined" aria-hidden="true">delete_outline</span></button>
        <button type="button" class="fm-icon-button fm-pos__summary-mobile-close" @click="cartOpen=false" aria-label="Tutup keranjang"><span class="material-symbols-outlined" aria-hidden="true">close</span></button>
      </div></div>
      <div class="fm-pos__basket">
        @forelse($cart as $id => $item)
          <div class="fm-pos__basket-item" wire:key="cart-item-{{ $id }}">
            <div class="fm-pos__basket-img">
              @if(!empty($item['image']))<img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}">
              @else<span class="material-symbols-outlined" aria-hidden="true">inventory_2</span>@endif
            </div>
            <div class="fm-pos__basket-details"><p class="fm-pos__basket-name">{{ $item['name'] }}</p><p class="fm-pos__basket-price">Rp {{ number_format($item['price'], 0, ',', '.') }} / item</p>
              <div class="fm-pos__stepper">
                <button type="button" wire:click="decreaseQty({{ $id }})" aria-label="Kurangi {{ $item['name'] }}"><span class="material-symbols-outlined" aria-hidden="true">remove</span></button>
                <span class="fm-pos__qty" aria-label="{{ $item['qty'] }} unit">{{ $item['qty'] }}</span>
                <button type="button" wire:click="increaseQty({{ $id }})" aria-label="Tambah {{ $item['name'] }}"><span class="material-symbols-outlined" aria-hidden="true">add</span></button>
              </div>
            </div>
            <span class="fm-pos__basket-total">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
          </div>
        @empty
          <div class="fm-pos__empty"><span class="material-symbols-outlined" aria-hidden="true" style="font-size:36px;display:block;margin-bottom:9px">shopping_cart</span>Keranjang masih kosong.<br>Pilih produk untuk memulai transaksi.</div>
        @endforelse
      </div>
      <div class="fm-pos__checkout">
        <div class="fm-pos__receipt-row"><span>Subtotal</span><strong>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</strong></div>
        <div class="fm-pos__receipt-row"><span>Pajak ({{ number_format($tax * 100, 0) }}%)</span><strong>Rp {{ number_format($this->taxAmount, 0, ',', '.') }}</strong></div>
        <div class="fm-pos__receipt-total"><span>Total Tagihan</span><strong>Rp {{ number_format($this->total, 0, ',', '.') }}</strong></div>
        <button type="button" wire:click="checkout" wire:loading.attr="disabled" wire:target="checkout" @disabled(empty($cart)) class="fm-button fm-pos__pay">
          <span wire:loading.remove wire:target="checkout" class="material-symbols-outlined" aria-hidden="true">payments</span>
          <span wire:loading wire:target="checkout" class="material-symbols-outlined" aria-hidden="true">progress_activity</span>
          <span wire:loading.remove wire:target="checkout">Proses Pembayaran</span>
          <span wire:loading wire:target="checkout">Memproses…</span>
        </button>
        <p class="fm-note" style="text-align:center;margin-top:10px">Metode pembayaran: Tunai</p>
      </div>
    </aside>
  </div>

  <div class="fm-modal" x-data="{show:false}" x-show="show" x-cloak x-on:payment-successful.window="show=true" @keydown.escape.window="show=false">
    <div class="fm-modal__scrim" @click="show=false"></div>
    <section class="fm-modal__panel" role="dialog" aria-modal="true" aria-label="Pembayaran berhasil" style="max-width:400px">
      <div class="fm-modal__body" style="padding:34px;text-align:center"><span class="fm-success-mark"><span class="material-symbols-outlined" style="font-size:35px" aria-hidden="true">check_circle</span></span><h2 class="fm-modal__title">Pembayaran Berhasil!</h2><p class="fm-muted" style="font-size:12px;margin:10px 0 23px">Pesanan tersimpan dan stok produk sudah diperbarui.</p><button type="button" class="fm-button" style="width:100%" @click="show=false;cartOpen=false">Selesai</button></div>
    </section>
  </div>

  <div class="fm-toast" x-data="{show:false,message:'',type:'error'}" x-show="show" x-cloak x-on:notify.window="message=$event.detail.message;type=$event.detail.type;show=true;setTimeout(()=>show=false,3000)" :data-tone="type==='error' ? 'danger' : 'success'" role="status">
    <span class="material-symbols-outlined" aria-hidden="true" x-text="type==='error' ? 'error_outline' : 'check_circle'"></span><span x-text="message"></span>
  </div>
</div>