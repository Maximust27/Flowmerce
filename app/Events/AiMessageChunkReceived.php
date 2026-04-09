<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AiMessageChunkReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $chunk;
    public $isDone;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $chunk, $isDone = false)
    {
        $this->userId = $userId;
        $this->chunk = $chunk;
        $this->isDone = $isDone;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ai-chat.' . $this->userId),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'AiMessageChunkReceived';
    }
}
