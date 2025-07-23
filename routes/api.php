<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\AccountController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'store'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('auth/me', [AccountController::class, 'index']);

    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index']);
        Route::post('/store', [UsersController::class, 'store']);
        Route::put('/{user:id}/update', [UsersController::class, 'update']);
        Route::delete('/{user:id}/delete', [UsersController::class, 'delete']);
    });
});