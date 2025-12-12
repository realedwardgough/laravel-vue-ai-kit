<?php

namespace App\Http\Controllers\Gemini;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use Inertia\Inertia;
use Inertia\Response;

class GeminiController extends Controller
{
    public function show(ChatService $service): Response
    {
        return Inertia::render('gemini/Gemini', [
            'chat' => $service->retrieve(auth()->user()),
        ]);
    }
}
