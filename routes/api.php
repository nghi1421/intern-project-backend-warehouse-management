<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GeneratePDFController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseBranchController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])
    ->name('login');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('get-permission', [AuthController::class, 'getPermission'])
        ->name('get-permission');

    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::resource('staffs', StaffController::class);

    Route::resource('users', UserController::class);

    Route::resource('positions', PositionController::class);

    Route::resource('categories', CategoryController::class);

    Route::resource('warehouse-branches', WarehouseBranchController::class);

    Route::resource('imports', ImportController::class);

    Route::get('log', [ImportController::class, 'log']);

    Route::resource('exports', ExportController::class);

    Route::resource('providers', ProviderController::class);

    Route::resource('stocks', StockController::class);

    Route::get('/pdf/import/{importId}', [GeneratePDFController::class, 'import']);

    Route::get('/pdf/export/{exportId}', [GeneratePDFController::class, 'export']);

    Route::get('/permissions', [PermissionController::class, 'index']);

    Route::get('/roles', [RoleController::class, 'index']);

    Route::get('track', [TrackController::class, '__invoke']);
});

Route::get('test', function () {
    return new JsonResponse('123');
});
