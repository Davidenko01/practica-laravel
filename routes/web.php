<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\IdeaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index');

    Route::post('/ideas', [IdeaController::class, 'store']);

    Route::get('/ideas/create', [IdeaController::class, 'create']);

    Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('idea.show');

    Route::get('/ideas/{idea}/edit', [IdeaController::class, 'edit']);

    Route::patch('/ideas/{idea}', [IdeaController::class, 'update']);

    Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy']);

    Route::delete('/logout', [SessionController::class, 'destroy']);
});

Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisterUserController::class, 'create']);

    Route::post('/register', [RegisterUserController::class, 'store']);

    Route::post('/login', [SessionController::class, 'store']);

    Route::get('/login', [SessionController::class, 'create'])->name('login');
});

Route::get('admin', fn () => 'Vista del Panel Admin')->can('view-admin-panel');

Route::view('/', 'welcome');
