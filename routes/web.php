<?php

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
    
    // 
    Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');
    Route::get('/gemini', [GeminiController::class, 'show'])->name('geminiShow');

    //
    Route::post('/chat', [MessageController::class, 'store'])->name('sendMessage');
});

/**
 * Development Broadcast testing
 */
Route::get('/test-broadcast', function () {
    
    $testMessage = [
        'chat_id' => 1,
        'content' => 'This is a test.',
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
