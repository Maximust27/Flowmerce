<div class="fm-page">
  <x-ui.page-header eyebrow="Business Intelligence" title="Asisten AI" description="Diskusikan stok, transaksi, dan ide bisnis melalui konsultan AI Flowmerce." />
  <x-ui.card padding="lg">
    <div class="fm-chat" x-data="{scrollToBottom(){this.$nextTick(()=>{const box=this.$refs.chatContainer;if(box)box.scrollTop=box.scrollHeight})}}" x-init="scrollToBottom()" @message-received.window="scrollToBottom()" @ai-thinking.window="scrollToBottom()">
      <div class="fm-chat__head"><div class="flex items-center gap-3"><span class="fm-brand__mark" style="height:41px;width:41px"><span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span></span><div><h2 class="fm-chat__title">Konsultan Bisnis</h2><p class="fm-chat__status">Pertanyaan tentang performa usaha dan operasional</p></div></div><x-ui.badge tone="success"><span class="material-symbols-outlined" style="font-size:13px" aria-hidden="true">bolt</span>AI Assistant</x-ui.badge></div>
      <div class="fm-chat__messages" x-ref="chatContainer" id="chat-messages" role="log" aria-label="Percakapan dengan Asisten AI" aria-live="polite" aria-relevant="additions text">
        @foreach($chatHistory as $index => $chat)
          @if(trim($chat['content'] ?? '') !== '')
            <div class="fm-chat__message" data-role="{{ $chat['role'] === 'user' ? 'user' : 'assistant' }}" wire:key="ai-message-{{ $index }}">
              <span class="fm-chat__avatar"><span class="material-symbols-outlined" aria-hidden="true">{{ $chat['role'] === 'user' ? 'person' : 'auto_awesome' }}</span></span>
              <div class="fm-chat__bubble">{{ $chat['content'] }}</div>
            </div>
          @endif
        @endforeach
        @if($isTyping)<div class="fm-chat__message" data-role="assistant"><span class="fm-chat__avatar"><span class="material-symbols-outlined" aria-hidden="true">auto_awesome</span></span><div class="fm-chat__typing" role="status" aria-label="Asisten sedang menyiapkan jawaban"><span></span><span></span><span></span></div></div>@endif
      </div>
      <div class="fm-chat__suggestions" aria-label="Pertanyaan yang disarankan">
        <button class="fm-pill" type="button" wire:click="$set('message', 'Apakah saya untung?')">Apakah saya untung?</button>
        <button class="fm-pill" type="button" wire:click="$set('message', 'Barang apa yang harus di-restock?')">Apa yang perlu di-restock?</button>
        <button class="fm-pill" type="button" wire:click="$set('message', 'Buat ide promo akhir pekan')">Ide promo akhir pekan</button>
        <button class="fm-pill" type="button" wire:click="$set('message', 'Analisis stok menipis')">Analisis stok menipis</button>
      </div>
      <form wire:submit.prevent="sendMessage" class="fm-chat__input-wrap">
        <label class="sr-only" for="chat-input">Pesan untuk Asisten AI</label>
        <input id="chat-input" class="fm-chat__input" type="text" wire:model="message" placeholder="Tanyakan sesuatu tentang bisnis Anda…" @disabled($isTyping) autocomplete="off">
        <button class="fm-chat__submit" type="submit" aria-label="Kirim pesan" @disabled($isTyping) wire:loading.attr="disabled" wire:target="sendMessage"><span class="material-symbols-outlined" aria-hidden="true">arrow_upward</span></button>
      </form>
      <p class="fm-chat__disclaimer">Jawaban AI dapat tidak akurat. Verifikasi informasi sebelum mengambil keputusan bisnis.</p>
    </div>
  </x-ui.card>
</div>