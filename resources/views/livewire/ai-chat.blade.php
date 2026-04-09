<div class="bg-gray-800/80 backdrop-blur-md border border-gray-700/50 rounded-2xl flex flex-col drop-shadow-xl h-[600px]" data-aos="fade-up" data-aos-duration="1400" x-data="{
    scrollToBottom() {
        requestAnimationFrame(() => {
            let container = this.$refs.chatContainer;
            container.scrollTop = container.scrollHeight;
        });
    }
}" x-init="
    scrollToBottom();
    Livewire.on('message-received', () => {
        scrollToBottom();
    });
    Livewire.on('ai-thinking', () => {
        scrollToBottom();
    });
">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-700/50 flex items-center bg-gray-850 rounded-t-2xl">
        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-cyan-500/20 mr-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white tracking-wide">Konsultan Bisnis AI</h2>
            <p class="text-xs text-green-400 font-medium">Online & Siap Membantu</p>
        </div>
    </div>

    <!-- Chat Area -->
    <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-blend-soft-light cursor-default">
        @foreach ($chatHistory as $chat)
            <div class="flex w-full {{ $chat['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] rounded-2xl p-4 {{ $chat['role'] === 'user' ? 'bg-indigo-600 text-white rounded-br-none shadow-lg shadow-indigo-600/20' : 'bg-gray-700 text-gray-200 rounded-bl-none shadow-lg border border-gray-600' }}">
                    {!! nl2br(e($chat['content'])) !!}
                </div>
            </div>
        @endforeach

        @if($isTyping)
            <div class="flex w-full justify-start">
                <div class="max-w-[75%] rounded-2xl p-4 bg-gray-700 text-gray-200 rounded-bl-none shadow-lg border border-gray-600 flex space-x-2 items-center">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        @endif
    </div>

    <!-- Input Area -->
    <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-850 rounded-b-2xl">
        <form wire:submit.prevent="sendMessage" class="flex items-center space-x-4">
            <input wire:model="message" type="text" placeholder="Tanyakan strategi hari ini..." class="flex-1 bg-gray-900 border border-gray-600 text-white rounded-full px-6 py-3 focus:outline-none focus:border-cyan-500 transition-colors shadow-inner" {{ $isTyping ? 'disabled' : '' }}>
            <button type="submit" class="bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-400 hover:to-indigo-400 rounded-full p-3 font-medium transition-all shadow-lg shadow-cyan-500/30 transform hover:scale-105" {{ $isTyping ? 'disabled' : '' }}>
                <svg class="w-6 h-6 text-white transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
    </div>

    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #4B5563;
        border-radius: 10px;
    }
    </style>
</div>
