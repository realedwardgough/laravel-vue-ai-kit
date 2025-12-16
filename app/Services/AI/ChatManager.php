<?php

declare(strict_types= 1);

namespace App\Services\AI;

use App\Models\Chat;
use App\Services\AI\Factory\ChatProviderFactory;

class ChatManager
{
    public function __construct(
        private ChatProviderFactory $factory
    ){}

    public function handle(Chat $chat, string $message, string $provider): void
    {
        $provider = $this->factory->make($provider);
        $provider->send($chat, $message);
    }
}