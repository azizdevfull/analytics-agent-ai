<?php

use App\Http\Controllers\OrderAiController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/orders/chat/start', [OrderAiController::class, 'store']);
Route::post('/orders/chat/continue', [OrderAiController::class, 'continue']);

Route::get('/orders', [OrderController::class, 'index']);

Route::get('/order/stream', [OrderAiController::class, 'stream']);

Route::get('/orders/{id}', [OrderController::class, 'show']);