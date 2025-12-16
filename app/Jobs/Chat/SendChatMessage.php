<?php

namespace App\Jobs\Chat;

use App\Models\Chat;
use App\Services\AI\ChatManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendChatMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 30;
    public int $tries = 3;

    public function __construct(
        public int $chatId,
        public string $message,
        public string $provider
    ) {}

    public function handle(ChatManager $chatManager): void
    {

        $chat = Chat::find($this->chatId);
        if (!$chat) return;

        $chatManager->handle(
            chat: $chat,
            message: $this->message,
            provider: $this->provider,
        );
                
    }
}
