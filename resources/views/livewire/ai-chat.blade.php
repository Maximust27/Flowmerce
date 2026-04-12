<div class="flex flex-col h-[calc(100vh-120px)]" x-data="{
    scrollToBottom() {
        this.$nextTick(() => {
            let container = this.$refs.chatContainer;
            if(container) container.scrollTop = container.scrollHeight;
        });
    }
}" x-init="
    scrollToBottom();
    Livewire.on('message-received', () => scrollToBottom());
    Livewire.on('ai-thinking', () => scrollToBottom());
">

    {{-- Chat Header --}}
    <div class="flex items-center gap-4 pb-4 border-b border-white/5 mb-4">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-600 to-primary flex items-center justify-center shrink-0 ai-glow">
            <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">smart_toy</span>
        </div>
        <div>
            <h2 class="text-lg font-bold text-white">Konsultan Bisnis AI</h2>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-xs font-medium text-primary">Online & Siap Membantu</span>
            </div>
        </div>
    </div>

    {{-- Chat Messages Area --}}
    <div x-ref="chatContainer" class="flex-1 overflow-y-auto space-y-8 no-scrollbar" id="chat-messages">
        @foreach ($chatHistory as $chat)
            @if ($chat['role'] === 'user')
            {{-- User Message --}}
            <div class="flex justify-end items-start gap-4">
                <div class="chat-bubble-user">
                    <p class="text-sm leading-relaxed">{!! nl2br(e($chat['content'])) !!}</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center border border-white/10 shrink-0">
                    <span class="material-symbols-outlined text-xs">person</span>
                </div>
            </div>
            @else
            {{-- AI Message --}}
            <div class="flex justify-start items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-600 to-primary flex items-center justify-center shrink-0 ai-glow">
                    <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">smart_toy</span>
                </div>
                <div class="chat-bubble-ai">
                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{!! nl2br(e($chat['content'])) !!}</p>
                </div>
            </div>
            @endif
        @endforeach

        @if($isTyping)
        {{-- Typing Indicator --}}
        <div class="flex justify-start items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-slate-500 text-sm">smart_toy</span>
            </div>
            <div class="flex gap-1.5 px-4 py-3 rounded-full bg-surface-container-low border border-white/5">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-600 opacity-40 animate-bounce" style="animation-delay: 0ms;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-violet-600 opacity-70 animate-bounce" style="animation-delay: 150ms;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-violet-600 animate-bounce" style="animation-delay: 300ms;"></span>
            </div>
        </div>
        @endif
    </div>

    {{-- Suggested Prompts --}}
    <div class="flex flex-wrap gap-2 py-3 overflow-x-auto no-scrollbar stagger-enter" id="suggested-prompts">
        <button wire:click="$set('message', 'Apakah saya untung?')" class="whitespace-nowrap px-4 py-2 rounded-full border border-white/10 bg-surface-container-high/30 text-xs font-medium text-slate-300 hover:border-primary/50 hover:text-primary transition-all active:scale-95">
            Apakah saya untung?
        </button>
        <button wire:click="$set('message', 'Barang apa yang harus di-restock?')" class="whitespace-nowrap px-4 py-2 rounded-full border border-white/10 bg-surface-container-high/30 text-xs font-medium text-slate-300 hover:border-primary/50 hover:text-primary transition-all active:scale-95">
            Barang apa yang harus di-restock?
        </button>
        <button wire:click="$set('message', 'Buat ide promo akhir pekan')" class="whitespace-nowrap px-4 py-2 rounded-full border border-white/10 bg-surface-container-high/30 text-xs font-medium text-slate-300 hover:border-primary/50 hover:text-primary transition-all active:scale-95">
            Buat ide promo akhir pekan
        </button>
        <button wire:click="$set('message', 'Analisis stok menipis')" class="whitespace-nowrap px-4 py-2 rounded-full border border-white/10 bg-surface-container-high/30 text-xs font-medium text-slate-300 hover:border-primary/50 hover:text-primary transition-all active:scale-95">
            Analisis stok menipis
        </button>
    </div>

    {{-- Input Bar --}}
    <div class="relative group pt-3 border-t border-white/5">
        <div class="absolute -inset-0.5 bg-gradient-to-r from-violet-600 to-primary rounded-2xl blur opacity-10 group-focus-within:opacity-30 transition duration-500"></div>
        <form wire:submit.prevent="sendMessage" class="relative flex items-center bg-surface-container-low rounded-2xl p-2 border border-white/10">
            <input wire:model="message" class="flex-1 !bg-transparent !border-none !shadow-none !ring-0 text-sm text-on-surface px-4 placeholder:text-slate-600" type="text" placeholder="Tanya sesuatu ke Asisten AI..." id="chat-input" {{ $isTyping ? 'disabled' : '' }}>
            <button type="submit" class="bg-primary text-on-primary p-3 rounded-xl hover:scale-105 active:scale-95 transition-all flex items-center justify-center" id="btn-send-chat" {{ $isTyping ? 'disabled' : '' }}>
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">send</span>
            </button>
        </form>
        <p class="text-[10px] text-center text-slate-600 mt-3 uppercase tracking-widest font-bold">Flowmerce AI dapat membuat kesalahan. Periksa info penting.</p>
    </div>

</div>
