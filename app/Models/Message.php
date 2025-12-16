<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Chat;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'chat_id',
        'content',
        'user_or_model',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
