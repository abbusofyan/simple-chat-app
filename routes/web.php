<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ChatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\RemoteAuthMiddleware;
use App\Http\Middleware\HandleInertiaRequests;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([RemoteAuthMiddleware::class, HandleInertiaRequests::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/chat', ChatController::class);
    Route::get('/chat/room/{conversation}', [ChatController::class, 'room'])->name('chat.room');
    Route::get('/chat/get-conversation/{conversation}', [ChatController::class, 'getConversation'])->name('chat.get-conversation');
    Route::get('/chat/{conversation}/messages', [ChatController::class, 'getConversationMessages'])->name('chat.get-conversation-messages');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

    Route::resource('/contact', ContactController::class);
    Route::get('/contact/start-chat/{contact}', [ContactController::class, 'startChat'])->name('contact.start-chat');

});

require __DIR__.'/auth.php';
