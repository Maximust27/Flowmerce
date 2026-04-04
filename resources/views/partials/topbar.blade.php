{{-- TopAppBar --}}
<header class="w-full h-16 sticky top-0 z-40 bg-slate-900/60 backdrop-blur-lg border-b border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.3)] flex justify-between items-center px-8">
    <div class="flex items-center gap-4">
        {{-- Mobile hamburger --}}
        <button @click="sidebarOpen = true" class="md:hidden text-slate-400 hover:text-white transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>

        @auth
        @php
            $hour = now()->format('H');
            if ($hour < 12) $greeting = 'Selamat pagi';
            elseif ($hour < 15) $greeting = 'Selamat siang';
            elseif ($hour < 18) $greeting = 'Selamat sore';
            else $greeting = 'Selamat malam';
        @endphp
        <span class="text-lg font-semibold text-slate-100">{{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }} 👋</span>
        @endauth
    </div>

    <div class="flex items-center gap-4">
        {{-- Notification Bell --}}
        <button class="p-2 text-slate-300 hover:text-white hover:bg-white/5 rounded-full transition-colors active:opacity-80" id="notification-btn">
            <span class="material-symbols-outlined">notifications</span>
        </button>

        {{-- User Profile --}}
        @auth
        <div class="flex items-center gap-3 pl-4 border-l border-white/10">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-slate-100 leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-primary uppercase tracking-widest mt-1">Owner</p>
            </div>
            <a href="{{ route('profile') }}" wire:navigate class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary/20 hover:border-primary transition-colors cursor-pointer active:scale-95 bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-on-primary font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </a>
        </div>
        @endauth
    </div>
</header>
