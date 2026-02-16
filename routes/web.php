<?php

use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::middleware(['guest'])->group(function() {
    Route::get('/register', [RegisterUserController::class, 'create']);
    Route::post('/register', [RegisterUserController::class, 'store']);

    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/login', [SessionsController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::delete('/logout', [SessionsController::class, 'destroy']);
});


