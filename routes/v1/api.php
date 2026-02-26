<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\HoldingController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refreshToken']);
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::prefix('v1/holdings')->middleware('auth:api')->group(function () {
    Route::get('/', [HoldingController::class, 'index']);
    Route::get('/{id}', [HoldingController::class, 'show']);
    Route::post('/', [HoldingController::class, 'store']);
    Route::put('/{id}', [HoldingController::class, 'update']);
    Route::delete('/{id}', [HoldingController::class, 'destroy']);
});

Route::prefix('v1/users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->middleware(['auth:api']);
    Route::get('/{id}', [UserController::class, 'show'])->middleware(['auth:api']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update'])->middleware(['auth:api']);
    Route::delete('/{id}', [UserController::class, 'destroy'])->middleware(['auth:api']);
});
