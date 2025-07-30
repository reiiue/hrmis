<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PDSController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/pds', [PDSController::class, 'index'])->name('pds.index');
    Route::post('/pds/update', [PDSController::class, 'update'])->name('pds.update');
    
});
