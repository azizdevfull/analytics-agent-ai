<?php

use App\Http\Controllers\AnalyticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/analytic/orders', [AnalyticController::class, 'orderAnalytic']);
Route::post('/analytic/orders/continue', [AnalyticController::class, 'orderAnalyticContinue']);