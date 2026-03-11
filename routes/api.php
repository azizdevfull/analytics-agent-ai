<?php

use App\Http\Controllers\OrderAiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/orders/chat/start', [OrderAiController::class, 'store']);
Route::post('/orders/chat/continue', [OrderAiController::class, 'continue']);
Route::post('/orders/chat/stream', [OrderAiController::class, 'stream']);