<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});



/**
 * Current working & testing broadcasting channel for the gemini chat events
 */
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return true;
});
