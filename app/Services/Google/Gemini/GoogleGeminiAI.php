<?php

declare(strict_types=1);

namespace App\Services\Google\Gemini;

class GoogleGeminiAI
{

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = env("GEMINI_API_KEY");
    }
}