<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])
    ->name('login');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::prefix('manager')->group(function () {
        Route::resource('staffs', StaffController::class);
    });
});



Route::get('test', function () {
    return new JsonResponse('123');
});
