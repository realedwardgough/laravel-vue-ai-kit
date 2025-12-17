<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResponseEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $chatId,
        public string $message
    ){}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("chat.{$this->chatId}"),
        ];
    }

    public function broadcastWith(): array {
        return [
            'chatId' => $this->chatId,
            'message' => $this->message,
        ];
    }

    public function broadcastAs()
    {
        return 'response.event';
    }
}
