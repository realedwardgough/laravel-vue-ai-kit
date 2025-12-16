<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use Illuminate\Http\RedirectResponse;

class ChatController extends Controller
{
    public function store(ChatService $service): RedirectResponse
    {
        $service->create();
        return back();
    }
}
