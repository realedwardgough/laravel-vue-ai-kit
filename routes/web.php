<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Laravel\ViewController;
use App\Http\Controllers\Gemini\GeminiController;

/**
 * None Authenticated Routes
 */
Route::get('/', [ViewController::class, 'landing'])->name('home');

/**
 * Authenticated + Verified Access Routes
 */
Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');
    Route::get('/gemini', [GeminiController::class, 'show'])->name('geminiShow');
});

require __DIR__.'/settings.php';
