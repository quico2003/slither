<?php

use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // Routes AdminPortal
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('users', [AdminUserController::class, 'index']);
        Route::post('users', [AdminUserController::class, 'store']);
        Route::put('users/{user}', [AdminUserController::class, 'update']);
        Route::delete('users/{user}', [AdminUserController::class, 'destroy']);
        Route::get('media', [AdminMediaController::class, 'index']);
    });

    // Routes UserPortal
    Route::middleware('role:user')->prefix('user')->group(function () {
        Route::get('media', [MediaController::class, 'index']);
        Route::post('media', [MediaController::class, 'store']);
        Route::delete('media/{media}', [MediaController::class, 'destroy']);
        Route::put('profile/password', [AuthController::class, 'changePassword']);
    });
});
