<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::controller(UserController::class)
            ->prefix('user')
            ->group(function () {
                Route::get('/me', 'me');
                Route::put('/me', 'update');
            });
        Route::controller(NoteController::class)
            ->prefix('note')
            ->group(function () {
                Route::post('/create', 'create');

                Route::get('/list', 'list');

                Route::get('/{note:id}', 'show');
                Route::put('/{note:id}', 'update');

                Route::delete('/{note:id}', 'destroy');
            });
    });
