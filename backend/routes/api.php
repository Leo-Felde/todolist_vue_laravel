<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubTarefaController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\TarefaUsuarioController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('usuario', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categorias', CategoriaController::class);
    Route::apiResource('sub-tarefas', SubTarefaController::class);
    Route::apiResource('tarefas', TarefaController::class);
    Route::apiResource('tarefas-usuarios', TarefaUsuarioController::class);
});

