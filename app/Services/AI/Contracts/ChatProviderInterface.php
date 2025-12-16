<?php

declare(strict_types= 1);

namespace App\Services\AI\Contracts;

use App\Models\Chat;
use App\Services\AI\DTO\ChatResponse;

interface ChatProviderInterface 
{
    public function send(Chat $chat, string $message): ChatResponse;
}