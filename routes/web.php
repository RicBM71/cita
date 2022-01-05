<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified', 'lortad'])->get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum', 'verified', 'lortad'])->get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::middleware(['auth:sanctum', 'verified'])->get('lortad', [App\Http\Controllers\LortadController::class, 'index'])->name('lortad');
Route::middleware(['auth:sanctum', 'verified'])->put('lortad', [App\Http\Controllers\LortadController::class, 'update'])->name('lortad.update');

Route::middleware(['auth:sanctum', 'verified', 'lortad'])->get('book/{fecha}/{turno}', [App\Http\Controllers\ReservasController::class, 'index'])->name('book');
Route::middleware(['auth:sanctum', 'verified', 'lortad'])->post('book/create', [App\Http\Controllers\ReservasController::class, 'store'])->name('book.store');
Route::middleware(['auth:sanctum', 'verified', 'lortad'])->delete('book/{cita}', [App\Http\Controllers\ReservasController::class, 'destroy'])->name('book.destroy');
