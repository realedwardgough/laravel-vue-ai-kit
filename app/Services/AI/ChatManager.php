<?php

declare(strict_types= 1);

namespace App\Services\AI;

use App\Events\Chat\ResponseEvent;
use App\Models\Chat;
use App\Models\Message;
use App\Services\AI\DTO\ChatResponse;
use App\Services\AI\Factory\ChatProviderFactory;

class ChatManager
{
    public function __construct(
        private ChatProviderFactory $factory
    ){}

    public function handle(Chat $chat, string $message, string $provider): void
    {
        $provider = $this->factory->make($provider);
        $response = $provider->send($chat, $message);

        $this->postMessage($chat, $response);
        $this->updateTitle($chat, $response);

    }

    private function postMessage(Chat $chat, ChatResponse $response): void
    {
        Message::create([
            'chat_id' => $chat->id,
            'content' => $response->content,
            'user_or_model' => 2,
        ]);

        broadcast(new ResponseEvent($chat->id, $response->content));
    }

    private function updateTitle(Chat $chat, ChatResponse $response): void
    {   
        if (
            $response->title &&
            in_array($chat->name, [null, '', 'New Chat'], true)
        ) {
            $chat->update([
                'name' => $response->title,
            ]);
        }
    }
}