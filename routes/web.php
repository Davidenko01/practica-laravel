<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index');

    Route::post('/ideas', [IdeaController::class, 'store'])->name('idea.store');

    Route::get('/ideas/create', [IdeaController::class, 'create'])->name('idea.create');

    Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('idea.show');

    Route::get('/ideas/{idea}/edit', [IdeaController::class, 'edit'])->name('idea.edit');

    Route::patch('/ideas/{idea}', [IdeaController::class, 'update'])->name('idea.update');

    Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])->name('idea.destroy');

    Route::patch('/steps/{step}', [StepController::class, 'update'])->name('step.update');

    Route::delete('/logout', [SessionController::class, 'destroy']);

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisterUserController::class, 'create']);

    Route::post('/register', [RegisterUserController::class, 'store']);

    Route::post('/login', [SessionController::class, 'store']);

    Route::get('/login', [SessionController::class, 'create'])->name('login');
});

Route::get('admin', fn () => 'Vista del Panel Admin')->can('view-admin-panel');

Route::view('/', 'welcome');
