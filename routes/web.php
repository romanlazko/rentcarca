<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Bots;
use App\Livewire\Chats;
use App\Livewire\Logs;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.bots');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('bots', Bots::class)->name('bots');
        Route::get('{telegram_bot}/chats', Chats::class)->name('chats');
        Route::get('{telegram_bot}/logs', Logs::class)->name('logs');
    });
});

require __DIR__.'/auth.php';
