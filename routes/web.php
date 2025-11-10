<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PDSController;
use App\Http\Controllers\PdsPdfController;
use App\Http\Controllers\SALNController;
use App\Http\Controllers\SalnPdfController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\EmployeeRecordsController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| These routes are accessible to guests (not logged in).
| The homepage lets users choose between Employee and Admin.
*/
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication
Route::get('/login/{role?}', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

/*
|--------------------------------------------------------------------------
| Dashboards
|--------------------------------------------------------------------------
| Each role gets a separate dashboard.
*/
Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', 'role:Admin'])
    ->name('admin.dashboard');

Route::get('/hr/dashboard', [DashboardController::class, 'hr'])
    ->middleware(['auth', 'role:HR'])
    ->name('hr.dashboard');

Route::get('/employee/dashboard', [DashboardController::class, 'employee'])
    ->middleware(['auth', 'role:Employee'])
    ->name('employee.dashboard');


    /*
    |--------------------------------------------------------------------------
    | PDS (Personal Data Sheet) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('pds')->name('pds.')->group(function () {
        Route::get('/', [PDSController::class, 'index'])->name('index');
        Route::post('/update', [PDSController::class, 'update'])->name('update');
        // Employee/Admin PDS download route
        Route::get('/pds/download-pdf/{userId?}', [PdsPdfController::class, 'download'])->name('pds.pdf');

        Route::post('/submit', [PDSController::class, 'submitPDS'])->name('submit');

    });

    /*
    |--------------------------------------------------------------------------
    | SALN (Statement of Assets, Liabilities, and Net Worth) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('saln')->name('saln.')->group(function () {
        Route::get('/', [SALNController::class, 'index'])->name('index');
        Route::post('/store', [SALNController::class, 'update'])->name('update');
        Route::get('/download', [SalnPdfController::class, 'download'])->name('download');
    });
});

Route::middleware(['auth', 'role:Admin'])->group(function () {

    // User Management
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

    // ----------------------------
    // Employee Records (PDS & SALN)
    // ----------------------------
    Route::get('/admin/employees', [EmployeeRecordsController::class, 'index'])->name('admin.employee.records');
    Route::get('/admin/employees/{user}/pds', [EmployeeRecordsController::class, 'showPDS'])->name('admin.employee.pds.show');
    Route::get('/admin/employees/{user}/saln', [EmployeeRecordsController::class, 'showSALN'])->name('admin.employee.saln.show');
    // Admin view/download PDS of any employee
    Route::get('/admin/employee/pds/{userId}', [PdsPdfController::class, 'download'])
        ->name('admin.employee.pds.show');

    // Admin view/download SALN of any employee
    Route::get('/admin/employee/saln/{userId}', [SalnPdfController::class, 'download'])
        ->name('admin.employee.saln.show');

    Route::post('/employee/{id}/pds/action', [EmployeeRecordsController::class, 'pdsAction'])
        ->name('admin.employee.records.pds-action');

    Route::post('/employee/{id}/saln/action', [EmployeeRecordsController::class, 'salnAction'])
        ->name('admin.employee.records.saln-action');

});
