<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Product;

class AiChat extends Component
{
    public $message = '';
    public $chatHistory = [];
    public $isTyping = false;
    public $userId;

    public function mount()
    {
        $this->userId = Auth::id();
        $this->chatHistory[] = [
            'role' => 'assistant', 
            'content' => 'Halo! Saya asisten bisnis AI Anda. Ada yang bisa saya bantu terkait laporan keuangan, analisis stok, atau strategi pemasaran hari ini?'
        ];
    }

    public function sendMessage()
    {
        if(trim($this->message) === '') return;

        $userMsg = $this->message;
        
        $this->chatHistory[] = [
            'role' => 'user',
            'content' => $userMsg
        ];
        
        $this->message = '';
        $this->isTyping = true;
        
        // Add an empty assistant message to append chunks to
        $this->chatHistory[] = [
            'role' => 'assistant',
            'content' => '' // Will be filled by Echo
        ];
        
        $this->dispatch('ai-thinking');
        
        $this->getAiResponse($userMsg);
    }
    
    public function getAiResponse($userMsg)
    {
        $businessInfo = Auth::user()->only(['name', 'business_name', 'business_category']);
        
        $revenue = Transaction::where('type', 'INCOME')->sum('amount');
        $expenses = Transaction::where('type', 'EXPENSE')->sum('amount');
        $lowStock = Product::whereColumn('current_stock', '<=', 'min_stock_alert')->pluck('name');
        
        $context = [
            'business_profile' => $businessInfo,
            'financials' => [
                'revenue' => (float) $revenue,
                'expenses' => (float) $expenses,
                'profit' => (float) ($revenue - $expenses)
            ],
            'low_stock_alerts' => $lowStock->toArray()
        ];
        
        $apiKey = env('GEMINI_API_KEY');
        
        if(empty($apiKey)) {
            $this->chatHistory[count($this->chatHistory)-1]['content'] = "Maaf, GEMINI_API_KEY belum dikonfigurasi di .env system.";
            $this->isTyping = false;
            return;
        }

        $prompt = "Anda adalah konsultan bisnis AI untuk UMKM Indonesia. Gunakan data JSON berikut untuk menjawab pertanyaan pengguna. Berikan jawaban dalam format Markdown singkat. \nData: " . json_encode($context) . "\n\nPertanyaan Pengguna: " . $userMsg;

        // Dispatch Job to handle streaming in background
        \App\Jobs\ProcessAiChat::dispatch($userId, $prompt, $apiKey);
    }
    
    #[\Livewire\Attributes\On('echo-private:ai-chat.{userId},AiMessageChunkReceived')]
    public function handleAiChunk($event)
    {
        $this->isTyping = false;
        
        if (!isset($this->chatHistory[count($this->chatHistory)-1])) return;
        
        if ($event['isDone']) {
            $this->dispatch('message-received');
            return;
        }
        
        $this->chatHistory[count($this->chatHistory)-1]['content'] .= $event['chunk'];
        $this->dispatch('message-received');
    }

    public function render()
    {
        return view('livewire.ai-chat');
    }
}
