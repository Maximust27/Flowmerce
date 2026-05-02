{{-- Sidebar Component --}}
<div class="flex flex-col h-full">

    {{-- Logo & Business Identity --}}
    <div class="mb-10">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-on-primary shadow-lg ai-glow-emerald">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">hub</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tighter text-primary">Flowmerce</h1>
                @auth
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest">Warung {{ auth()->user()->name ?? 'Warung' }}</p>
                @endauth
            </div>
        </a>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 space-y-2">
        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           wire:navigate>
            <span class="material-symbols-outlined" @if(request()->routeIs('dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>dashboard</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('inventaris.index') }}"
           class="sidebar-link {{ request()->routeIs('inventaris.*') ? 'active' : '' }}"
           wire:navigate>
            <span class="material-symbols-outlined" @if(request()->routeIs('inventaris.*')) style="font-variation-settings: 'FILL' 1;" @endif>inventory_2</span>
            <span>Inventaris</span>
        </a>

        <a href="{{ route('gudang.index') }}"
           class="sidebar-link {{ request()->routeIs('gudang.*') ? 'active' : '' }}"
           wire:navigate>
            <span class="material-symbols-outlined" @if(request()->routeIs('gudang.*')) style="font-variation-settings: 'FILL' 1;" @endif>shelves</span>
            <span>Gudang Digital</span>
        </a>

        <a href="{{ route('keuangan.index') }}"
           class="sidebar-link {{ request()->routeIs('keuangan.*') ? 'active' : '' }}"
           wire:navigate>
            <span class="material-symbols-outlined" @if(request()->routeIs('keuangan.*')) style="font-variation-settings: 'FILL' 1;" @endif>payments</span>
            <span>Keuangan</span>
        </a>

        <a href="{{ route('pegawai.index') }}"
           class="sidebar-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}"
           wire:navigate>
            <span class="material-symbols-outlined" @if(request()->routeIs('pegawai.*')) style="font-variation-settings: 'FILL' 1;" @endif>group</span>
            <span>Manajemen Pegawai</span>
        </a>

        <a href="{{ route('meja.index') }}"
           class="sidebar-link {{ request()->routeIs('meja.*') ? 'active' : '' }}"
           wire:navigate>
            <span class="material-symbols-outlined" @if(request()->routeIs('meja.*')) style="font-variation-settings: 'FILL' 1;" @endif>table_restaurant</span>
            <span>Scan to Order</span>
        </a>
    </nav>

    {{-- Bottom Section --}}
    <div class="mt-auto space-y-6">
        {{-- AI Assistant Button --}}
        <a href="{{ route('chat.index') }}"
           class="w-full flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-violet-600 to-sky-600 text-white rounded-xl font-bold text-sm hover:scale-[1.02] active:scale-95 transition-transform shadow-lg shadow-violet-500/20"
           wire:navigate>
            <span class="material-symbols-outlined">smart_toy</span>
            Asisten AI
        </a>

        <div class="space-y-1">
            <a href="{{ route('chat.index') }}"
               class="sidebar-link {{ request()->routeIs('chat.*') ? 'active' : '' }}"
               wire:navigate>
                <span class="material-symbols-outlined">chat</span>
                <span>Chat AI</span>
            </a>

            <a href="{{ route('profile') }}"
               class="sidebar-link {{ request()->routeIs('profile') ? 'active' : '' }}"
               wire:navigate>
                <span class="material-symbols-outlined">settings</span>
                <span>Pengaturan</span>
            </a>
        </div>

        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link w-full text-left hover:!text-error">
                <span class="material-symbols-outlined">logout</span>
                <span>Keluar</span>
            </button>
        </form>
        @endauth
    </div>
</div>
