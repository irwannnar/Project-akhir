<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrintingController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SpendingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route untuk guest (yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route yang memerlukan authentication
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Redirect root ke login

Route::get('/', function () {
    return view('components.auth.login');
});

Route::resource('dashboard', DashboardController::class);

Route::get('/home', function () {
    return view('welcome');
});

Route::resource('printing', PrintingController::class);

Route::resource('order', OrderController::class);

Route::resource('sale', SaleController::class);

Route::resource('finance', FinanceController::class);

Route::resource('spending', SpendingController::class);

Route::resource('invoice', InvoiceController::class);
