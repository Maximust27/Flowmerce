<div class="fm-page">
  <x-ui.page-header eyebrow="Produk & Persediaan" title="Inventaris" description="Kelola katalog produk, pantau ketersediaan, dan jaga nilai stok tetap terkendali.">
    <x-slot:actions><x-ui.button wire:click="openModal" id="btn-tambah-produk"><span class="material-symbols-outlined" aria-hidden="true">add</span> Tambah Produk</x-ui.button></x-slot:actions>
  </x-ui.page-header>

  @if(session()->has('message'))<x-ui.feedback>{{ session('message') }}</x-ui.feedback>@endif

  @php
    $userId = auth()->id();
    $totalProducts = \App\Models\Product::where('user_id', $userId)->count();
    $outOfStock = \App\Models\Product::where('user_id', $userId)->where('current_stock', 0)->count();
    $lowStock = \App\Models\Product::where('user_id', $userId)->where('current_stock', '>', 0)->whereColumn('current_stock', '<=', 'min_stock_alert')->count();
    $stockAsset = \App\Models\Product::where('user_id', $userId)->selectRaw('SUM(buy_price * current_stock) as total')->value('total') ?? 0;
  @endphp

  <section class="fm-kpi-grid fm-kpi-grid--four" aria-label="Ringkasan inventaris">
    <x-ui.stat-card label="Total Produk" :value="number_format($totalProducts, 0, ',', '.')" icon="category" hint="Produk terdaftar" />
    <x-ui.stat-card label="Stok Habis" :value="number_format($outOfStock, 0, ',', '.')" icon="production_quantity_limits" tone="warning" hint="Perlu ditambahkan" />
    <x-ui.stat-card label="Stok Menipis" :value="number_format($lowStock, 0, ',', '.')" icon="inventory" tone="warning" hint="Di bawah batas aman" />
    <x-ui.stat-card label="Nilai Aset Stok" :value="'Rp '.number_format($stockAsset, 0, ',', '.')" icon="wallet" variant="accent" hint="Estimasi dari harga beli" />
  </section>

  <x-ui.section-header title="Daftar Produk" description="Informasi katalog beserta harga dan status ketersediaan.">
    <x-slot:actions><x-ui.badge tone="accent">{{ number_format($totalProducts, 0, ',', '.') }} produk</x-ui.badge></x-slot:actions>
  </x-ui.section-header>

  <div class="fm-list-toolbar" role="search" aria-label="Cari dan filter inventaris">
    <label class="fm-list-toolbar__search" for="fm-inventory-search">
      <span class="material-symbols-outlined" aria-hidden="true">search</span>
      <input id="fm-inventory-search" type="search" placeholder="Cari produk atau kategori..." wire:model.live.debounce.350ms="search" autocomplete="off">
    </label>
    <div class="fm-list-toolbar__filters">
      <label class="fm-list-toolbar__select"><span class="sr-only">Filter kategori</span><select wire:model.live="categoryFilter">
        <option value="all">Semua kategori</option>
        @foreach($categories as $category)<option value="{{ $category }}">{{ $category }}</option>@endforeach
      </select><span class="material-symbols-outlined" aria-hidden="true">expand_more</span></label>
      <label class="fm-list-toolbar__select"><span class="sr-only">Filter status stok</span><select wire:model.live="stockFilter">
        <option value="all">Semua stok</option>
        <option value="normal">Stok aman</option>
        <option value="low">Stok menipis</option>
        <option value="out">Stok habis</option>
      </select><span class="material-symbols-outlined" aria-hidden="true">expand_more</span></label>
    </div>
  </div>
  <x-ui.table label="Daftar inventaris produk">
    <x-slot:head><tr><th scope="col">Nama produk</th><th scope="col">Harga beli</th><th scope="col">Harga jual</th><th scope="col">Stok tersedia</th><th scope="col" class="text-right">Tindakan</th></tr></x-slot:head>
    @forelse($products as $product)
      <tr wire:key="product-{{ $product->id }}">
        <td><div class="fm-data-table__product">
          @if($product->image)<img src="{{ Storage::url($product->image) }}" class="fm-data-table__img" alt="Foto {{ $product->name }}" loading="lazy">
          @else <div class="fm-data-table__img fm-data-table__placeholder"><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span></div>@endif
          <div><p class="fm-data-table__product-name">{{ $product->name }}</p><p class="fm-data-table__secondary">{{ $product->category ?: 'Tanpa kategori' }} · #{{ $product->id }}</p></div>
        </div></td>
        <td class="fm-mono fm-nowrap">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
        <td class="fm-mono fm-nowrap">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
        <td>@if($product->current_stock <= 0)<x-ui.badge tone="danger">Habis · 0 unit</x-ui.badge>
            @elseif($product->current_stock <= $product->min_stock_alert)<x-ui.badge tone="warning">Menipis · {{ $product->current_stock }} unit</x-ui.badge>
            @else<x-ui.badge tone="success">Aman · {{ $product->current_stock }} unit</x-ui.badge>@endif
        </td>
        <td><div class="fm-data-table__actions">
          <button type="button" class="fm-icon-button" wire:click="edit({{ $product->id }})" aria-label="Ubah {{ $product->name }}" title="Ubah produk"><span class="material-symbols-outlined" aria-hidden="true">edit</span></button>
          <button type="button" class="fm-icon-button" data-tone="danger" wire:click="delete({{ $product->id }})" wire:confirm="Yakin ingin menghapus produk ini?" aria-label="Hapus {{ $product->name }}" title="Hapus produk"><span class="material-symbols-outlined" aria-hidden="true">delete_outline</span></button>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="5"><x-ui.empty-state icon="inventory_2" title="Produk tidak ditemukan" description="Coba ubah pencarian atau filter, atau tambahkan produk baru." /></td></tr>
    @endforelse
    <x-slot:footer>{{ $products->links('vendor.pagination.custom', data: ['scrollTo' => false]) }}</x-slot:footer>
  </x-ui.table>

  @if($isModalOpen)
  <x-ui.modal :title="$product_id ? 'Edit Produk' : 'Tambah Produk'">
    <form wire:submit.prevent="save" class="fm-form">
      <x-ui.field field="name" label="Nama Produk" wire:model="name" placeholder="Contoh: Kopi Susu Gula Aren" required />
      <x-ui.field field="category" label="Kategori" wire:model="category" placeholder="Contoh: Minuman" />
      <div class="fm-field"><label class="fm-field__label" for="product-image">Foto Produk</label>
        <input id="product-image" type="file" wire:model="image" accept="image/*" class="fm-field__input">
        <p class="fm-upload-hint" wire:loading wire:target="image">Mengunggah gambar…</p>
        @if($image)<img class="fm-file-preview" src="{{ $image->temporaryUrl() }}" alt="Pratinjau foto produk">
        @elseif($old_image)<img class="fm-file-preview" src="{{ Storage::url($old_image) }}" alt="Foto produk saat ini">@endif
        @error('image')<p class="fm-field__error" role="alert">{{ $message }}</p>@enderror
      </div>
      <div class="fm-form__grid">
        <x-ui.field field="buy_price" label="Harga Beli (Rp)" type="number" min="0" step="any" wire:model="buy_price" placeholder="0" required />
        <x-ui.field field="sell_price" label="Harga Jual (Rp)" type="number" min="0" step="any" wire:model="sell_price" placeholder="0" required />
      </div>
      <div class="fm-form__grid">
        <x-ui.field field="current_stock" label="Jumlah Stok" type="number" min="0" wire:model="current_stock" placeholder="0" required />
        <x-ui.field field="min_stock_alert" label="Peringatan Stok ≤" type="number" min="0" wire:model="min_stock_alert" placeholder="5" required />
      </div>
      <div class="fm-form__actions"><x-ui.button variant="secondary" wire:click="closeModal">Batal</x-ui.button><x-ui.button type="submit" wire:loading.attr="disabled" wire:target="save"><span class="material-symbols-outlined" aria-hidden="true">save</span>Simpan Produk</x-ui.button></div>
    </form>
  </x-ui.modal>
  @endif
</div>