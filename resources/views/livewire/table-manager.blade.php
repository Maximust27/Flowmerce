<div class="fm-page fm-tables">
  <x-ui.page-header eyebrow="OPERASIONAL / SCAN TO ORDER" title="Meja & QR" description="Buat meja, bagikan QR menu, dan pantau pesanan pelanggan dari setiap meja.">
    <x-slot:actions>
      <x-ui.button variant="secondary" wire:click="openQrisUpload">
        <span class="material-symbols-outlined" aria-hidden="true">qr_code_2</span>QR pembayaran
      </x-ui.button>
      <x-ui.button wire:click="openAdd">
        <span class="material-symbols-outlined" aria-hidden="true">add</span>Tambah meja
      </x-ui.button>
    </x-slot:actions>
  </x-ui.page-header>

  @if(session()->has('message'))
    <x-ui.feedback>{{ session('message') }}</x-ui.feedback>
  @endif

  <section class="fm-kpi-grid" aria-label="Ringkasan meja">
    <x-ui.stat-card label="Total meja" :value="$tables->count()" icon="table_restaurant" hint="Meja terdaftar" />
    <x-ui.stat-card label="Meja aktif" :value="$tables->where('is_active', true)->count()" icon="check_circle" tone="success" hint="QR menu tersedia" />
    <x-ui.stat-card label="Pesanan masuk" :value="$pendingCount" icon="receipt_long" variant="accent" hint="Menunggu diproses kasir" />
  </section>

  <div class="fm-tables__notice" data-status="{{ $qrisImage ? 'ready' : 'setup' }}">
    <div class="fm-tables__notice-icon"><span class="material-symbols-outlined" aria-hidden="true">{{ $qrisImage ? 'verified' : 'info' }}</span></div>
    <div class="fm-tables__notice-body">
      <strong>{{ $qrisImage ? 'QR pembayaran sudah tersedia' : 'QR pembayaran belum diunggah' }}</strong>
      <p>{{ $qrisImage ? 'Pelanggan dapat memilih QRIS, tetapi kasir harus memverifikasi pembayaran secara manual.' : 'Pelanggan tetap bisa memesan dan membayar di kasir. Unggah gambar QRIS jika diperlukan.' }}</p>
    </div>
    @if($qrisImage)
      <img class="fm-tables__payment-preview" src="{{ Storage::url($qrisImage) }}" alt="QR pembayaran usaha" loading="lazy">
    @endif
  </div>

  <x-ui.section-header title="Daftar meja" description="QR tiap meja mengarah ke menu publik yang berbeda.">
    <x-slot:actions><x-ui.badge tone="accent">{{ $tables->count() }} / 20 meja</x-ui.badge></x-slot:actions>
  </x-ui.section-header>

  <section class="fm-tables__grid" aria-label="Daftar meja yang dikelola">
    @forelse($tables as $table)
      <article class="fm-tables__card" wire:key="table-{{ $table->id }}">
        <div class="fm-tables__card-top">
          <span class="fm-tables__card-icon"><span class="material-symbols-outlined" aria-hidden="true">table_restaurant</span></span>
          <x-ui.badge :tone="$table->is_active ? 'success' : 'warning'">{{ $table->is_active ? 'Aktif' : 'Nonaktif' }}</x-ui.badge>
        </div>
        <h3>{{ $table->table_number }}</h3>
        <p class="fm-tables__code">{{ $table->table_code }}</p>
        <div class="fm-tables__card-actions">
          <button type="button" class="fm-tables__qr-action" wire:click="openQr({{ $table->id }})"><span class="material-symbols-outlined" aria-hidden="true">qr_code_2</span> Lihat QR</button>
          <div class="fm-tables__icon-actions">
            <button type="button" wire:click="openEdit({{ $table->id }})" title="Ubah meja" aria-label="Ubah {{ $table->table_number }}"><span class="material-symbols-outlined" aria-hidden="true">edit</span></button>
            <button type="button" wire:click="toggleActive({{ $table->id }})" title="{{ $table->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" aria-label="{{ $table->is_active ? 'Nonaktifkan' : 'Aktifkan' }} {{ $table->table_number }}"><span class="material-symbols-outlined" aria-hidden="true">{{ $table->is_active ? 'toggle_off' : 'toggle_on' }}</span></button>
            <button type="button" wire:click="delete({{ $table->id }})" wire:confirm="Hapus meja beserta histori pesanan yang terkait?" title="Hapus meja" aria-label="Hapus {{ $table->table_number }}"><span class="material-symbols-outlined" aria-hidden="true">delete_outline</span></button>
          </div>
        </div>
      </article>
    @empty
      <x-ui.card padding="lg" class="fm-tables__empty"><x-ui.empty-state icon="table_restaurant" title="Belum ada meja" description="Tambahkan meja untuk membuat QR menu pelanggan." /></x-ui.card>
    @endforelse
  </section>

  @if($showModal)
    <x-ui.modal :title="$editingId ? 'Ubah meja' : 'Tambah meja'" close="closeModal">
      <form wire:submit="save" class="fm-form">
        <x-ui.field field="table_number" label="Nama atau nomor meja" wire:model="table_number" placeholder="Contoh: Meja 01 atau VIP 02" required />
        <label class="fm-tables__switch-row" for="fm-table-active">
          <span><strong>Meja aktif</strong><small>Izinkan pelanggan membuka menu melalui QR ini.</small></span>
          <input type="checkbox" id="fm-table-active" wire:model="is_active">
        </label>
        <div class="fm-form__actions">
          <x-ui.button variant="secondary" wire:click="closeModal">Batal</x-ui.button>
          <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="save">Simpan meja</x-ui.button>
        </div>
      </form>
    </x-ui.modal>
  @endif

  @if($showQrModal && $selectedTable)
    @php($menuUrl = $this->getMenuUrl($selectedTable['table_code']))
    <x-ui.modal :title="'QR '.$selectedTable['table_number']" close="closeModal">
      <div class="fm-tables__qr-dialog">
        <div class="fm-tables__qr-frame">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&amp;margin=12&amp;data={{ urlencode($menuUrl) }}"
               alt="QR menu {{ $selectedTable['table_number'] }}" width="240" height="240">
        </div>
        <p>Pelanggan dapat memindai QR ini untuk membuka menu dan membuat pesanan dari {{ $selectedTable['table_number'] }}.</p>
        <label class="fm-tables__url"><span>Alamat menu meja</span><input type="text" readonly value="{{ $menuUrl }}" aria-label="Alamat menu meja"></label>
        <div class="fm-tables__dialog-actions">
          <button type="button" class="fm-button" data-variant="secondary" data-url="{{ $menuUrl }}" x-on:click="navigator.clipboard.writeText($el.dataset.url)">Salin tautan</button>
          <a class="fm-button" href="{{ $menuUrl }}" target="_blank" rel="noopener noreferrer"><span class="material-symbols-outlined" aria-hidden="true">open_in_new</span>Buka menu</a>
        </div>
        <p class="fm-tables__qr-help">Cetak QR dan tempatkan di meja. Pastikan alamat aplikasi dapat diakses dari ponsel pelanggan.</p>
      </div>
    </x-ui.modal>
  @endif

  @if($showQrisModal)
    <x-ui.modal title="QR pembayaran usaha" close="closeModal">
      <form wire:submit="saveQrisQr" class="fm-form">
        <p class="fm-note">Unggah foto QRIS yang valid dari penyedia pembayaran. Sistem belum memverifikasi transfer otomatis; kasir wajib mencocokkan mutasi sebelum menyelesaikan pesanan QRIS.</p>
        <div class="fm-field">
          <label class="fm-field__label" for="fm-qris-file">Foto QR pembayaran</label>
          <input id="fm-qris-file" type="file" accept="image/*" class="fm-field__input" wire:model="qrisFile">
          <p class="fm-field__hint">PNG atau JPG, maksimal 2 MB.</p>
          @error('qrisFile')<p class="fm-field__error" role="alert">{{ $message }}</p>@enderror
          @if($qrisFile)<img class="fm-file-preview" src="{{ $qrisFile->temporaryUrl() }}" alt="Pratinjau QR pembayaran">@endif
        </div>
        <div class="fm-form__actions"><x-ui.button variant="secondary" wire:click="closeModal">Batal</x-ui.button><x-ui.button type="submit" wire:loading.attr="disabled" wire:target="saveQrisQr">Simpan QRIS</x-ui.button></div>
      </form>
    </x-ui.modal>
  @endif
</div>