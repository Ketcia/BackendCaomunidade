<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MensagemController;
use App\Http\Controllers\API\TopicoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::resource("topico",TopicoController::class);
    Route::resource("mensagem",MensagemController::class);

});