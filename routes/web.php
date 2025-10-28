<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PDSController;
use App\Http\Controllers\PdsPdfController;
use App\Http\Controllers\SALNController;
use App\Http\Controllers\SalnPdfController;

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
Route::post('/login', [AuthController::class, 'login']);
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
