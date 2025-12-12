<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\MessageRequest;
use App\Services\Chat\MessageService;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    public function store(MessageRequest $request, MessageService $service): RedirectResponse
    {
        $service->create($request->validated());
        return back();
    }
}
