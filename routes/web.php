<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(env('APP_URL') . '/panel');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
