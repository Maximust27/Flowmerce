<?php

namespace App\Livewire;

use App\Services\BusinessAdvisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AiChat extends Component
{
    public string $message = '';
    public array $chatHistory = [];
    public bool $isTyping = false;

    public function mount(): void
    {
        $this->chatHistory = [[
            'role' => 'assistant',
            'content' => 'Selamat datang di konsultasi bisnis Flowmerce. Saya dapat membantu membaca pencatatan keuangan, memeriksa stok, dan menyusun langkah pengembangan usaha.',
        ]];
    }

    public function sendMessage(BusinessAdvisor $advisor): void
    {
        $this->validate(['message' => ['required', 'string', 'max:1200']]);

        $question = trim($this->message);
        if ($question === '') return;

        $this->chatHistory[] = ['role' => 'user', 'content' => $question];
        $this->chatHistory[] = ['role' => 'assistant', 'content' => ''];
        // Keep the Livewire payload compact, particularly on mobile.
        $this->chatHistory = array_slice($this->chatHistory, -24);
        $this->message = '';
        $this->isTyping = true;
        $this->dispatch('ai-thinking');

        $index = array_key_last($this->chatHistory);

        try {
            $apiKey = config('services.gemini.api_key');
            if (!$apiKey) {
                $this->chatHistory[$index]['content'] = 'Asisten bisnis belum tersedia. Hubungi pengelola aplikasi untuk mengaktifkan layanan konsultasi.';
                return;
            }

            $context = $advisor->contextFor(Auth::user());
            $response = Http::timeout(25)->acceptJson()
                ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key='.urlencode($apiKey), [
                    'systemInstruction' => [
                        'parts' => [['text' => $advisor->instructions()]],
                    ],
                    'contents' => [[
                        'role' => 'user',
                        'parts' => [[
                            'text' => "Data bisnis terverifikasi dari database aplikasi (JSON):\n".
                                json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).
                                "\n\nPertanyaan pemilik usaha: ".$question,
                        ]],
                    ]],
                    'generationConfig' => [
                        'temperature' => 0.35,
                        'maxOutputTokens' => 650,
                    ],
                ]);

            if ($response->successful()) {
                $this->chatHistory[$index]['content'] = $advisor->plainText(
                    $response->json('candidates.0.content.parts.0.text')
                );
            } elseif ($response->status() === 429) {
                $this->chatHistory[$index]['content'] = 'Layanan konsultasi sedang sibuk. Coba kembali beberapa saat lagi.';
            } else {
                // Do not log the response body: it may contain user business data.
                Log::warning('Business advisor provider returned a non-success response', ['status' => $response->status()]);
                $this->chatHistory[$index]['content'] = 'Analisis belum dapat diproses saat ini. Silakan coba lagi.';
            }
        } catch (\Throwable $exception) {
            Log::warning('Business advisor is temporarily unavailable', ['exception' => get_class($exception)]);
            $this->chatHistory[$index]['content'] = 'Koneksi ke layanan konsultasi sedang bermasalah. Silakan coba kembali.';
        } finally {
            $this->isTyping = false;
            $this->dispatch('message-received');
        }
    }

    public function render()
    {
        return view('livewire.ai-chat');
    }
}
