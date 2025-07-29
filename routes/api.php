<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\AsignacionController;

// Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('leads', LeadController::class);
    Route::middleware('auth:sanctum')->get('/leads', [LeadController::class, 'index']);

    Route::put('operadores/{usuario}/cambiar-modo', [OperadorController::class, 'cambiarModo'])
        ->name('operadores.cambiarModo');

// Route::middleware(['auth:sanctum', 'role:admin|operador'])->group(function () {
    Route::apiResource('operadores', OperadorController::class);


Route::middleware(['role:admin'])->group(function () {
    Route::apiResource('asignaciones', AsignacionController::class);
});

