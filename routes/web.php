<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PDSController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SALNController;


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
    Route::get('/download-pdf', [PDFController::class, 'download'])->name('pdf.download');
});


// SALN Routes
Route::middleware(['auth'])->group(function () {
    // SALN index page
    Route::get('/saln', [SALNController::class, 'index'])->name('saln.index');

    // Save SALN form
    Route::post('/saln/store', [SALNController::class, 'update'])->name('saln.update');

    // Download PDF (optional, if you want it like PDS)
    Route::get('/saln/pdf', [SALNController::class, 'downloadPdf'])->name('saln.pdf');
});

