{{-- Mobile BottomNavBar --}}
<nav class="bottom-nav md:hidden">
    <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" wire:navigate>
        <span class="material-symbols-outlined" @if(request()->routeIs('dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>home</span>
        <span>Home</span>
    </a>

    <a href="{{ route('inventaris.index') }}" class="bottom-nav-item {{ request()->routeIs('inventaris.*') ? 'active' : '' }}" wire:navigate>
        <span class="material-symbols-outlined" @if(request()->routeIs('inventaris.*')) style="font-variation-settings: 'FILL' 1;" @endif>inventory</span>
        <span>Stok</span>
    </a>

    {{-- Central FAB --}}
    <div class="relative" x-data="{ fabOpen: false }">
        <button @click="fabOpen = !fabOpen" class="fab" id="fab-quick-action">
            <span class="material-symbols-outlined text-lg" :class="fabOpen ? 'rotate-45' : ''" style="transition: transform 0.2s;">add</span>
        </button>

        {{-- FAB Menu --}}
        <div x-show="fabOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="absolute bottom-16 left-1/2 -translate-x-1/2 flex flex-col gap-2 items-center"
             @click.outside="fabOpen = false"
             x-cloak>
            <a href="{{ route('inventaris.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl glass-card text-sm font-medium text-on-surface whitespace-nowrap" wire:navigate>
                <span class="material-symbols-outlined text-primary text-sm">add_circle</span>
                Tambah Produk
            </a>
            <a href="{{ route('keuangan.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl glass-card text-sm font-medium text-on-surface whitespace-nowrap" wire:navigate>
                <span class="material-symbols-outlined text-secondary text-sm">add_card</span>
                Catat Pemasukan
            </a>
        </div>
    </div>

    <a href="{{ route('keuangan.index') }}" class="bottom-nav-item {{ request()->routeIs('keuangan.*') ? 'active' : '' }}" wire:navigate>
        <span class="material-symbols-outlined" @if(request()->routeIs('keuangan.*')) style="font-variation-settings: 'FILL' 1;" @endif>payments</span>
        <span>Uang</span>
    </a>

    <a href="{{ route('chat.index') }}" class="bottom-nav-item {{ request()->routeIs('chat.*') ? 'active' : '' }}" wire:navigate>
        <span class="material-symbols-outlined" @if(request()->routeIs('chat.*')) style="font-variation-settings: 'FILL' 1;" @endif>chat</span>
        <span>Chat</span>
    </a>
</nav>
