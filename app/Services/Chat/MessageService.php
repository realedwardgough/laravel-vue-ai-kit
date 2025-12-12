<?php

declare(strict_types=1);

namespace App\Services\Chat;

use App\Models\Message;
use App\Models\Chat;

class MessageService
{
    public function create($data): Message
    {

        // Retrieve or Create Chat
        $chat = Chat::firstOrCreate([
            'user_id' => auth()->id(),
        ]);
        
        // Push chat id into the message and return the model after creation
        $data['chat_id'] = $chat->id;
        $data['user_or_model'] = 1;
        return Message::create($data);

    }

    public function update(){}
    public function delete(){}
}