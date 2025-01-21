<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    Route::get('chat-dashboard', [ChatController::class, 'dashboard'])->middleware(['auth'])->name('chat-dash');
    Route::get('chat/{id}', [ChatController::class, 'chat'])->middleware(['auth'])->name('chat');
require __DIR__.'/auth.php';
