<?php

use Illuminate\Support\Facades\Broadcast;

/**
 * Current working & testing broadcasting channel for the gemini chat events
 */
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return true;
});
