<?php

declare(strict_types= 1);

namespace App\Services\AI\DTO;

class ChatResponse
{
    public function __construct(
        public string $content,
        public ?string $title = null,
        public ?string $model = null,
    ){}
}