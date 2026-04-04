<x-layouts.app :title="'Chat AI'">

    <div class="flex flex-col h-[calc(100vh-120px)]">

        {{-- Chat Header --}}
        <div class="flex items-center gap-4 pb-4 border-b border-white/5 mb-4">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-container-high/50 border border-white/5">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-xs font-medium text-primary">Asisten AI — 🟢 Online</span>
            </div>
        </div>

        {{-- Chat Messages Area --}}
        <div class="flex-1 overflow-y-auto space-y-8 no-scrollbar" id="chat-messages">

            {{-- Date Divider --}}
            <div class="flex justify-center">
                <span class="px-4 py-1 rounded-full bg-surface-container text-[10px] font-bold uppercase tracking-widest text-slate-500">Hari Ini</span>
            </div>

            {{-- User Message --}}
            <div class="flex justify-end items-start gap-4">
                <div class="chat-bubble-user">
                    <p class="text-sm leading-relaxed">Halo Asisten AI, bisakah kamu rangkum performa keuangan warung saya hari ini? Apakah saya untung?</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center border border-white/10 shrink-0">
                    <span class="material-symbols-outlined text-xs">person</span>
                </div>
            </div>

            {{-- AI Message with Financial Data --}}
            <div class="flex justify-start items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-600 to-primary flex items-center justify-center shrink-0 ai-glow">
                    <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">smart_toy</span>
                </div>
                <div class="chat-bubble-ai">
                    <p class="text-sm mb-4 leading-relaxed">Tentu, Bu Ani. Berdasarkan data transaksi hingga pukul 18:00 hari ini, berikut adalah ringkasan keuangan Anda:</p>

                    {{-- Financial Data Module --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="bg-surface-container-lowest/50 p-4 rounded-xl border border-white/5">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Revenue</p>
                            <p class="font-jb text-lg font-bold text-secondary">Rp 3.000.000</p>
                        </div>
                        <div class="bg-surface-container-lowest/50 p-4 rounded-xl border border-white/5">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Expense</p>
                            <p class="font-jb text-lg font-bold text-error">Rp 2.500.000</p>
                        </div>
                        <div class="bg-primary/10 p-4 rounded-xl border border-primary/20">
                            <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Net Profit</p>
                            <p class="font-jb text-xl font-bold text-primary">Rp 500.000</p>
                        </div>
                    </div>

                    <p class="text-sm leading-relaxed italic text-slate-400">
                        "Anda mencatatkan margin keuntungan sebesar 16.6% hari ini. Penjualan sembako meningkat 12% dibandingkan kemarin."
                    </p>
                </div>
            </div>

            {{-- AI Typing Indicator --}}
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
        </div>

        {{-- Suggested Prompts --}}
        <div class="flex flex-wrap gap-2 py-3 overflow-x-auto no-scrollbar" id="suggested-prompts">
            <button class="whitespace-nowrap px-4 py-2 rounded-full border border-white/10 bg-surface-container-high/30 text-xs font-medium text-slate-300 hover:border-primary/50 hover:text-primary transition-all active:scale-95">
                Apakah saya untung?
            </button>
            <button class="whitespace-nowrap px-4 py-2 rounded-full border border-white/10 bg-surface-container-high/30 text-xs font-medium text-slate-300 hover:border-primary/50 hover:text-primary transition-all active:scale-95">
                Barang apa yang harus di-restock?
            </button>
            <button class="whitespace-nowrap px-4 py-2 rounded-full border border-white/10 bg-surface-container-high/30 text-xs font-medium text-slate-300 hover:border-primary/50 hover:text-primary transition-all active:scale-95">
                Buat ide promo akhir pekan
            </button>
            <button class="whitespace-nowrap px-4 py-2 rounded-full border border-white/10 bg-surface-container-high/30 text-xs font-medium text-slate-300 hover:border-primary/50 hover:text-primary transition-all active:scale-95">
                Analisis stok menipis
            </button>
        </div>

        {{-- Input Bar --}}
        <div class="relative group pt-3 border-t border-white/5">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-violet-600 to-primary rounded-2xl blur opacity-10 group-focus-within:opacity-30 transition duration-500"></div>
            <div class="relative flex items-center bg-surface-container-low rounded-2xl p-2 border border-white/10">
                <button class="p-3 text-slate-500 hover:text-slate-300">
                    <span class="material-symbols-outlined">attach_file</span>
                </button>
                <input class="flex-1 !bg-transparent !border-none !shadow-none !ring-0 text-sm text-on-surface px-2 placeholder:text-slate-600" type="text" placeholder="Tanya sesuatu ke Asisten AI..." id="chat-input">
                <button class="p-3 text-slate-500 hover:text-slate-300">
                    <span class="material-symbols-outlined">mic</span>
                </button>
                <button class="bg-primary text-on-primary p-3 rounded-xl hover:scale-105 active:scale-95 transition-all flex items-center justify-center" id="btn-send-chat">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">send</span>
                </button>
            </div>
            <p class="text-[10px] text-center text-slate-600 mt-3 uppercase tracking-widest font-bold">Flowmerce AI dapat membuat kesalahan. Periksa info penting.</p>
        </div>

    </div>

</x-layouts.app>
