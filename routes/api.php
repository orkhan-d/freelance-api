<?php

use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserProfileController;
use \App\Http\Controllers\ServiceController;
use \App\Http\Controllers\ServiceReviewController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/ping', [Controller::class, 'ping']);

Route::middleware(AuthCheck::class) -> group(
    function() {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/auth', [UserController::class, 'get']);

        // profile routes
        Route::post('/auth/profile', [UserProfileController::class, 'fill'])
            ->missing(fn() => response()->error('Not found', 404));
        Route::get('/users/{user}/profile', [UserProfileController::class, 'get'])
            ->missing(fn() => response()->error('Not found', 404));
        Route::post('/auth/profile/avatar', [UserProfileController::class, 'updateAvatar'])
            ->missing(fn() => response()->error('Not found', 404));

        // service routes
        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/services/{service}/show', [ServiceController::class, 'show']);
        Route::get('/services/{service}/freelancer', [ServiceController::class, 'getFreelancer']);
        Route::get('/auth/services', [ServiceController::class, 'authIndex']);
        Route::get('/services/{service}/reviews', [ServiceReviewController::class, 'getComments'])
            ->missing(fn() => response()->error('Not found', 404));
        Route::post('/services', [ServiceController::class, 'create']);
        Route::post('/services/{service:id}/reviews', [ServiceReviewController::class, 'create'])
            ->missing(fn() => response()->error('Not found', 404));

        //order routes
        Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index']);
        Route::get('/auth/orders', [\App\Http\Controllers\OrderController::class, 'store']);
        Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store']);
    }
);
