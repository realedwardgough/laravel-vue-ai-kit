<?php

declare(strict_types= 1);

namespace App\Services\Chat;

use App\Models\Chat;
use App\Models\User;

class ChatService
{

    public function retrieve(User $user): Chat
    {
        return Chat::where("user_id", $user->id)->with('messages')->first();
    }

    public function delete(){}

}