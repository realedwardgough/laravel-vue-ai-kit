<?php

declare(strict_types= 1);

namespace App\Services\AI\Factory;

use App\Services\AI\Contracts\ChatProviderInterface;
use App\Services\AI\Providers\GeminiProvider;

class ChatProviderFactory
{
    public function __construct(
        private GeminiProvider $gemini
    ){}

    public function make(string $provider): ChatProviderInterface
    {
        return match ($provider) {
            'gemini' => $this->gemini,
            default  => throw new \InvalidArgumentException('Invalid Provider!'),
        };
    }
}