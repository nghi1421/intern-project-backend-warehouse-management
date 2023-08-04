<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseBranchController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])
    ->name('login');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::resource('staffs', StaffController::class);

    Route::resource('users', UserController::class);

    Route::resource('positions', PositionController::class);

    Route::resource('categories', CategoryController::class);

    Route::resource('warehouse-branches', WarehouseBranchController::class);

    Route::resource('imports', ImportController::class);

    Route::resource('providers', ProviderController::class);

    Route::resource('stocks', StockController::class);

    Route::resource('locations', LocationController::class);
});



Route::get('test', function () {
    return new JsonResponse('123');
});