<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PublicacaoController;
use App\Http\Controllers\API\CategoriaController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get("/publicacao",[AuthController::class,'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user',[UserController::class, 'update']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::resource("categoria",CategoriaController::class);
    Route::resource("publicacao",PublicacaoController::class)->except('index');

});
