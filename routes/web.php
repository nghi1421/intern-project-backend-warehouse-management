<?php

use App\Http\Controllers\GeneratePDFController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/pdf/import/{importId}', [GeneratePDFController::class, 'import']);

Route::get('/pdf/export/{exportId}', [GeneratePDFController::class, 'export']);