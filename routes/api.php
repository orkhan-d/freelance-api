<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserProfileController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/ping', [Controller::class, 'ping']);

Route::middleware(\App\Http\Middleware\AuthCheck::class) -> group(
    function() {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/auth', [UserController::class, 'get']);
        Route::patch('/users/{user:id}/profile', [UserProfileController::class, 'fill']);
        Route::get('/users/{user:id}/profile', [UserProfileController::class, 'get']);
    }
);
