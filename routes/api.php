<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\AccountController;
use App\Http\Controllers\Dashboard\AppController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\OrganizationController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\ZohoConfigController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'store'])->name('login');
Route::get('app', [AppController::class, 'index']);

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

    Route::prefix('organization')->group(function () {
        Route::get('/', [OrganizationController::class, 'index']);
        Route::post('/store', [OrganizationController::class, 'store']);
    });

    Route::prefix('zoho-config')->group(function () {
        Route::get('/', [ZohoConfigController::class, 'index']);
        Route::put('/{zohoConfig:id}/update', [ZohoConfigController::class, 'update']);
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/{organization:slug}/{customerId}', [CustomerController::class, 'show']);
    });

    // TODO Select
    Route::get('select-organization', [OrganizationController::class, 'select']);
});