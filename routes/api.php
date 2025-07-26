<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\BaseData\AccountController;
use App\Http\Controllers\Dashboard\BaseData\AppController;
use App\Http\Controllers\Dashboard\BaseData\DashboardController;
use App\Http\Controllers\Dashboard\BaseData\UsersController;
use App\Http\Controllers\Dashboard\OrganizationController;
use App\Http\Controllers\Dashboard\ZohoConfigController;
use App\Http\Controllers\Dashboard\ZohoCustomersController;
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
        Route::put('/{organization:slug}/update', [OrganizationController::class, 'update']);
    });

    Route::prefix('zoho-config')->group(function () {
        Route::get('/{organization:slug}', [ZohoConfigController::class, 'index']);
        Route::put('/{organization:slug}/update', [ZohoConfigController::class, 'update']);
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [ZohoCustomersController::class, 'index']);
        Route::post('/store', [ZohoCustomersController::class, 'store']);
        Route::get('/{organization:slug}/{customerId}', [ZohoCustomersController::class, 'show']);
        Route::put('/{organization:slug}/{customerId}', [ZohoCustomersController::class, 'update']);
    });

    // TODO Select
    Route::get('select-organization', [OrganizationController::class, 'select']);
});