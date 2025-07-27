<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OperadorController;



Route::apiResource('leads', LeadController::class);

Route::apiResource('operadores', OperadorController::class);




