<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/idea');

Route::middleware(['guest'])->group(function() {

    Route::get('/register', [RegisterUserController::class, 'create']);
    Route::post('/register', [RegisterUserController::class, 'store']);

    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::delete('/logout', [SessionsController::class, 'destroy']);

    Route::get('/idea', [IdeaController::class, 'index'])->name('idea.index');
    Route::get('/idea/{id}', [IdeaController::class, 'show'])->name('idea.show');
});


