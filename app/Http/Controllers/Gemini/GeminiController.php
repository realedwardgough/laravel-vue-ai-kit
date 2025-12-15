<?php

namespace App\Http\Controllers\Gemini;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use App\Models\Chat;
use Inertia\Inertia;
use Inertia\Response;

class GeminiController extends Controller
{
    public function showAll(ChatService $service): Response
    {
        return Inertia::render('gemini/Gemini', [
            'chats' => $service->retrieveAll(auth()->user()),
        ]);
    }

    public function showSingle(ChatService $service, Chat $chat): Response
    {  
        return Inertia::render('gemini/Chat', [
            'chat' => $service->retrieveSingle(auth()->user(), $chat),
        ]);
    }
}
