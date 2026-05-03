<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Manajemen Meja</h1>
            <p class="text-slate-400 mt-1">Kelola QR Code meja dan pengaturan QRIS.</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="openQrisUpload"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-container border border-white/10 text-sm font-semibold hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[18px] text-primary">qr_code_2</span>
                Upload QR Qris
            </button>
            <button wire:click="openAdd"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-container text-white text-sm font-bold hover:bg-[#059669] transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Meja
            </button>
        </div>
    </div>

    <!-- QRIS QR Status Banner -->
    <div class="mb-6 p-4 rounded-2xl border {{ $qrisImage ? 'border-primary/30 bg-primary-container/10' : 'border-yellow-500/30 bg-yellow-500/10' }}">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined {{ $qrisImage ? 'text-primary' : 'text-yellow-400' }}">
                {{ $qrisImage ? 'check_circle' : 'warning' }}
            </span>
            <div>
                @if($qrisImage)
                    <p class="text-sm font-semibold text-primary">QR Qris sudah dikonfigurasi ✅</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Pelanggan yang memilih QRIS akan melihat QR ini.</p>
                @else
                    <p class="text-sm font-semibold text-yellow-400">QR Qris belum dikonfigurasi</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Upload gambar QR Qris agar pelanggan bisa bayar via QRIS.</p>
                @endif
            </div>
            @if($qrisImage)
            <img src="{{ Storage::url($qrisImage) }}" alt="QR Qris" class="w-12 h-12 object-contain bg-white rounded-lg p-1 ml-auto"/>
            @endif
        </div>
    </div>

    <!-- Tables Grid -->
    @if(count($tables) > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach($tables as $table)
        <div class="bg-surface-container rounded-2xl p-4 border border-white/5 flex flex-col gap-3 hover:border-primary/20 transition-colors">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-bold text-on-surface">{{ $table['table_number'] }}</p>
                    <p class="text-[10px] font-mono text-on-surface-variant mt-0.5">{{ $table['table_code'] }}</p>
                </div>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                    {{ $table['is_active'] ? 'bg-primary-container/20 text-primary' : 'bg-error/10 text-error' }}">
                    {{ $table['is_active'] ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div class="flex flex-col gap-1.5 mt-auto">
                <button wire:click="openQr({{ $table['id'] }})"
                        class="w-full py-2 rounded-xl bg-surface-container-highest text-on-surface-variant hover:text-primary hover:bg-primary-container/10 text-xs font-semibold transition-colors flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">qr_code</span> Lihat QR
                </button>
                <div class="flex gap-1.5">
                    <button wire:click="openEdit({{ $table['id'] }})"
                            class="flex-1 py-1.5 rounded-lg bg-surface-container-highest text-on-surface-variant text-xs hover:text-on-surface transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined text-[14px]">edit</span>
                    </button>
                    <button wire:click="toggleActive({{ $table['id'] }})"
                            class="flex-1 py-1.5 rounded-lg bg-surface-container-highest text-on-surface-variant text-xs hover:text-on-surface transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined text-[14px]">{{ $table['is_active'] ? 'toggle_off' : 'toggle_on' }}</span>
                    </button>
                    <button wire:click="delete({{ $table['id'] }})"
                            wire:confirm="Yakin hapus meja ini?"
                            class="flex-1 py-1.5 rounded-lg bg-surface-container-highest text-on-surface-variant text-xs hover:text-error transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined text-[14px]">delete</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-24 text-on-surface-variant/40">
        <span class="material-symbols-outlined text-[64px] mb-4">table_restaurant</span>
        <p class="text-lg font-semibold">Belum ada meja</p>
        <p class="text-sm mt-1">Tambahkan meja untuk mulai menggunakan Scan to Order.</p>
    </div>
    @endif

    <!-- ===== MODAL TAMBAH/EDIT ===== -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-surface-container rounded-3xl p-8 w-full max-w-sm mx-4 shadow-2xl border border-white/10"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-xl font-bold mb-6">{{ $editingId ? 'Edit Meja' : 'Tambah Meja' }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-on-surface-variant mb-1 block">Nama / Nomor Meja</label>
                    <input wire:model="table_number" type="text" placeholder="Contoh: Meja 1, VIP 1"
                           class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40"/>
                    @error('table_number')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-between p-4 bg-surface-container-high rounded-xl">
                    <span class="text-sm font-semibold">Status Meja</span>
                    <button wire:click="$toggle('is_active')"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $is_active ? 'bg-primary-container' : 'bg-surface-container-highest' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button wire:click="$set('showModal', false)" class="flex-1 py-3 rounded-xl border border-white/10 text-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors">Batal</button>
                <button wire:click="save" class="flex-1 py-3 rounded-xl bg-primary-container text-white text-sm font-bold hover:bg-[#059669] transition-colors">Simpan</button>
            </div>
        </div>
    </div>
    @endif

    <!-- ===== MODAL QR CODE ===== -->
    @if($showQrModal && $selectedTable)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-surface-container rounded-3xl p-8 w-full max-w-sm mx-4 shadow-2xl border border-white/10 text-center">
            <h3 class="text-xl font-bold mb-1">{{ $selectedTable['table_number'] }}</h3>
            <p class="text-xs font-mono text-on-surface-variant mb-6">{{ $selectedTable['table_code'] }}</p>
            <div class="bg-white rounded-3xl p-4 inline-block mb-4">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($this->getMenuUrl($selectedTable['table_code'])) }}"
                     alt="QR Code {{ $selectedTable['table_number'] }}" class="w-[220px] h-[220px]"/>
            </div>
            <p class="text-xs text-on-surface-variant mb-1">URL Menu:</p>
            <p class="text-xs font-mono bg-surface-container-highest px-3 py-2 rounded-lg mb-6 break-all">{{ $this->getMenuUrl($selectedTable['table_code']) }}</p>
            <p class="text-xs text-on-surface-variant mb-6">Screenshot atau cetak QR ini dan tempelkan di meja.</p>
            <button wire:click="$set('showQrModal', false)" class="w-full py-3 rounded-xl bg-surface-container-high font-semibold text-sm hover:bg-surface-container-highest transition-colors">Tutup</button>
        </div>
    </div>
    @endif

    <!-- ===== MODAL UPLOAD QRIS QR ===== -->
    @if($showQrisModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-surface-container rounded-3xl p-8 w-full max-w-sm mx-4 shadow-2xl border border-white/10">
            <h3 class="text-xl font-bold mb-2">Upload QR Qris</h3>
            <p class="text-sm text-on-surface-variant mb-6">Upload foto/screenshot QR Qris Anda. Gambar ini akan ditampilkan ke pelanggan yang memilih bayar via QRIS.</p>
            <div class="border-2 border-dashed border-white/20 rounded-2xl p-6 text-center mb-4">
                <input type="file" wire:model="qrisFile" accept="image/*" class="hidden" id="qrisFileInput"/>
                <label for="qrisFileInput" class="cursor-pointer flex flex-col items-center gap-2">
                    <span class="material-symbols-outlined text-[40px] text-on-surface-variant/50">upload</span>
                    <span class="text-sm text-on-surface-variant">Klik untuk pilih gambar</span>
                    <span class="text-xs text-on-surface-variant/50">PNG, JPG, maks 2MB</span>
                </label>
                @if($qrisFile)
                    <p class="text-xs text-primary mt-2 font-semibold">✅ File dipilih: {{ $qrisFile->getClientOriginalName() }}</p>
                @endif
            </div>
            @error('qrisFile')<p class="text-xs text-error mb-3">{{ $message }}</p>@enderror
            <div class="flex gap-3">
                <button wire:click="$set('showQrisModal', false)" class="flex-1 py-3 rounded-xl border border-white/10 text-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors">Batal</button>
                <button wire:click="saveQrisQr" class="flex-1 py-3 rounded-xl bg-primary-container text-white text-sm font-bold hover:bg-[#059669] transition-colors">Simpan</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Notification Toast -->
    <div x-data="{ show: false, message: '', type: 'success' }"
         x-on:notify.window="message = $event.detail.message; type = $event.detail.type; show = true; setTimeout(() => show = false, 3000)"
         x-show="show" x-transition style="display:none"
         class="fixed bottom-6 right-6 z-[100]">
        <div class="px-6 py-4 rounded-xl shadow-2xl border flex items-center gap-3 bg-surface-container-high"
             :class="type === 'error' ? 'border-error/30' : 'border-primary/30'">
            <span class="material-symbols-outlined" :class="type === 'error' ? 'text-error' : 'text-primary'"
                  x-text="type === 'error' ? 'error' : 'check_circle'"></span>
            <span class="text-sm font-medium text-on-surface" x-text="message"></span>
        </div>
    </div>
</div>
