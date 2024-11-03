<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ElephantController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/elephants', [ElephantController::class, 'index']);
    Route::get('/elephants/search', [ElephantController::class, 'search']);
    Route::post('/collection/add', [CollectionController::class, 'storeElephants']);
    Route::post('/trade', [TradeController::class, 'trade']);
});

