<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::controller(UserController::class)
            ->prefix('user')
            ->group(function () {
                Route::get('/me','me');
            });
        Route::controller(NoteController::class)
            ->prefix('note')
            ->group(function () {
                Route::post('/create', 'create');

                Route::get('/{note:id}', 'show');
                Route::put('/{note:id}', 'update');

                Route::delete('/{note:id}', 'destroy');
            });
});

