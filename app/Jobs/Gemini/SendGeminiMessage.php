<?php

namespace App\Jobs\Gemini;

use App\Models\Chat;
use App\Services\Google\Gemini\GoogleGeminiAI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\Gemini\GeminiThinkingEvent;

class SendGeminiMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 30;
    public int $tries = 3;

    public function __construct(
        public int $chatId,
        public string $message
    ) {}

    public function handle(GoogleGeminiAI $gemini): void
    {

        // Find or fail
        $chat = Chat::find($this->chatId);
        if (!$chat) return;

        // Start the thinking process
        broadcast(new GeminiThinkingEvent($chat->id, true));

        // Handle gemini request and response, then update thinking process
        try {
            $gemini->sendRequest($chat, $this->message);
        } finally {
            broadcast(new GeminiThinkingEvent($chat->id, false));
        }
                
    }
}
