<?php

use App\Http\Controllers\OperadorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AsignacionController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/operadores', [OperadorController::class, 'show'])->name('operadores.show');

    Route::get('/leads/create', function () {
    return Inertia::render('Leads/Create');
})->name('leads.create');
});

Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
// Route::get('/operadores', [OperadorController::class, 'show'])->name('operadores.show');



Route::get('/operadores/create', function () {
    return Inertia::render('Operadores/Create');
})->middleware('auth')->name('operadores.create');

// Registrar operador (usar controlador existente)
Route::post('/operadores', [RegisteredUserController::class, 'store'])
    ->name('operadores.store');

Route::put('/operadores/{usuario}/modo', [OperadorController::class, 'cambiarModo'])->name('operadores.cambiarModo');
Route::middleware(['auth', 'verified'])->get('/dashboard', [LeadController::class, 'index'])->name('dashboard');
Route::get('/asignaciones', function () {
    return Inertia::render('Asignaciones/Index');
})->name('asignaciones.index');
require __DIR__.'/auth.php';
