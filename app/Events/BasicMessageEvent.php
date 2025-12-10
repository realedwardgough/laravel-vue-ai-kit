<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BasicMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $chatId,
        public string $message
    )
    {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("chat.{$this->chatId}"),
        ];
    }

    /**
     * Get the data to broadcast on the channel
     * 
     * @return array{Example: string, Message: string}
     */
    public function broadcastWith(): array {

        \Log::info("BroadcastOn fired", [
            'channel' => "private-chat.{$this->chatId}",
            'chatId'  => $this->chatId,
            'message' => $this->message,
        ]);

        return [
            'channel' => "private-chat.{$this->chatId}",
            'chatId' => $this->chatId,
            'Message' => $this->message,
        ];
    }

    public function broadcastAs()
    {
        return 'BasicMessageEvent';
    }
}
