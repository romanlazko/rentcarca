<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'telegram:rentcarca_bot'])->name('rentcarca_bot.')->group(function () {
    Route::get('/page', function(){
        return view('rentcarca_bot::page');
    })->name('page');
});