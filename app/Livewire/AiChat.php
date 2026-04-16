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

        $prompt = "Anda adalah konsultan bisnis AI UMKM. Gunakan data ini untuk menjawab:\n" . 
                  "Owner: {$context['business_profile']['name']}\n" .
                  "Bisnis: {$context['business_profile']['business_name']}\n" .
                  "Kategori: {$context['business_profile']['business_category']}\n" .
                  "Keuangan: Rev: " . number_format($context['financials']['revenue']) . ", Exp: " . number_format($context['financials']['expenses']) . ", Profit: " . number_format($context['financials']['profit']) . "\n" .
                  "Stok Rendah: " . implode(', ', $context['low_stock_alerts']) . "\n\n" .
                  "User: " . $userMsg;

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
                $this->chatHistory[count($this->chatHistory)-1]['content'] = $text ?: "Maaf, AI tidak memberikan jawaban.";
            } elseif ($response->status() === 429) {
                $this->chatHistory[count($this->chatHistory)-1]['content'] = "Limit tercapai (Free Tier). Silakan tunggu ~60 detik sebelum pesan berikutnya.";
            } else {
                \Log::error("Gemini API Error [" . $response->status() . "]: " . $response->body());
                $this->chatHistory[count($this->chatHistory)-1]['content'] = "Maaf, ada masalah teknis (Error " . $response->status() . ").";
            }

        } catch (\Exception $e) {
            \Log::error("Gemini Request Error: " . $e->getMessage());
            $this->chatHistory[count($this->chatHistory)-1]['content'] = "Koneksi ke server AI terputus. Silakan coba lagi.";
        }

        $this->isTyping = false;
        $this->dispatch('message-received');
    }

    public function render()
    {
        return view('livewire.ai-chat');
    }
}
