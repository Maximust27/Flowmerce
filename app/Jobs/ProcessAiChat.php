<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Events\AiMessageChunkReceived;
use Illuminate\Support\Facades\Log;

class ProcessAiChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;
    public $prompt;
    public $apiKey;

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $prompt, $apiKey)
    {
        $this->userId = $userId;
        $this->prompt = $prompt;
        $this->apiKey = $apiKey;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [['text' => $this->prompt]]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
                
                if (empty($text)) {
                    broadcast(new AiMessageChunkReceived($this->userId, "Maaf, saya tidak dapat menghasilkan respon saat ini.", true));
                    return;
                }

                // Simulate streaming by breaking down the text
                $chunks = preg_split('/( +)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
                $buffer = '';
                
                foreach ($chunks as $chunk) {
                    $buffer .= $chunk;
                    // Broadcast every 2-3 words for smooth streaming effect
                    if (str_word_count($buffer) >= 2 || strlen($buffer) > 15) {
                        broadcast(new AiMessageChunkReceived($this->userId, $buffer, false));
                        usleep(30000); // 30ms simulation
                        $buffer = '';
                    }
                }
                
                if (!empty($buffer)) {
                    broadcast(new AiMessageChunkReceived($this->userId, $buffer, false));
                }
                
                // Final message to complete
                broadcast(new AiMessageChunkReceived($this->userId, "", true));
            } else {
                Log::error("Gemini API Error: " . $response->body());
                broadcast(new AiMessageChunkReceived($this->userId, "Maaf, terjadi kesalahan pada server AI.", true));
            }
        } catch (\Exception $e) {
            Log::error("Gemini Job Error: " . $e->getMessage());
            broadcast(new AiMessageChunkReceived($this->userId, "Maaf, sistem AI sedang offline.", true));
        }
    }
}
