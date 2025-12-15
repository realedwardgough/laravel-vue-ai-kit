<?php

declare(strict_types= 1);

namespace App\Services\Chat;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ChatService
{

    public function retrieveAll(User $user): Collection
    {
        return Chat::where('user_id', $user->id)->get();
    }

    public function retrieveSingle(User $user, Chat $chat): ?Chat
    {
        return Chat::with('messages')
            ->where('user_id', $user->id)
            ->where('id', $chat->id)
            ->first();
    }

    public function delete(){}

}