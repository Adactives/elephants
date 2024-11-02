<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ElephantController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/elephants', [ElephantController::class, 'index']);
    Route::post('/elephants/search', [ElephantController::class, 'search']);
});

