<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PDSController;
use App\Http\Controllers\PdsPdfController;
use App\Http\Controllers\SALNController;
use App\Http\Controllers\SalnPdfController;
use App\Http\Controllers\Admin\UserManagementController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| These routes are accessible to guests (not logged in).
| The homepage lets users choose between Employee and Admin.
*/
Route::get('/', function () {
    return view('auth.selection');
})->name('home');

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
Route::get('/admin/dashboard', function () {
    if (Auth::user()->role !== 'Admin') {
        abort(403, 'Unauthorized access.');
    }
    return view('dashboards.admin_dashboard');
})->name('admin.dashboard');

Route::get('/employee/dashboard', function () {
    if (Auth::user()->role !== 'Employee') {
        abort(403, 'Unauthorized access.');
    }
    return view('dashboards.employee_dashboard');
})->name('employee.dashboard');

Route::get('/hr/dashboard', function () {
    if (Auth::user()->role !== 'HR') {
        abort(403, 'Unauthorized access.');
    }
    return view('dashboards.hr_dashboard');
})->name('hr.dashboard');

    /*
    |--------------------------------------------------------------------------
    | PDS (Personal Data Sheet) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('pds')->name('pds.')->group(function () {
        Route::get('/', [PDSController::class, 'index'])->name('index');
        Route::post('/update', [PDSController::class, 'update'])->name('update');
        Route::get('/download-pdf', [PdsPdfController::class, 'download'])->name('pdf');
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
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});