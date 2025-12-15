<?php

use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\MessageController;
use App\Models\Message;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Laravel\ViewController;
use App\Http\Controllers\Gemini\GeminiController;
use App\Events\BasicMessageEvent;

/**
 * None Authenticated Routes
 */
Route::get('/', [ViewController::class, 'landing'])->name('home');

/**
 * Authenticated + Verified Access Routes
 */
Route::group(['middleware' => ['auth', 'verified']], function() {
    
    // Base routes
    Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');

    // Gemini specific routes
    Route::get('/gemini', [GeminiController::class, 'showAll'])->name('geminiShow');
    Route::get('gemini/chat/{chat}', [GeminiController::class,'showSingle'])->name('geminiChat');

    // Chat specific routes
    Route::post('/chat', [ChatController::class, 'store'])->name('createChat');
    Route::post('/message', [MessageController::class, 'store'])->name('sendMessage');
});

/**
 * Development Broadcast testing
 */
Route::get('/test-broadcast', function () {
    
    $testMessage = [
        'chat_id' => 1,
        'content' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
    ];
    
    broadcast(new BasicMessageEvent($testMessage['chat_id'], $testMessage['content']));

    Message::create([
        'chat_id' => $testMessage['chat_id'],
        'content' => $testMessage['content'],
        'user_or_model' => 2,
    ]);

    return 'Event Broadcast Sent.';
});

require __DIR__.'/settings.php';
