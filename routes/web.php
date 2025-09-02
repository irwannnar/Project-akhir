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
use App\Http\Controllers\ProductController;

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

//printing
// Route::resource('printing', PrintingController::class);

Route::get('/printing', [PrintingController::class, 'index']);

// Halaman create order
Route::get('/printing/create', [PrintingController::class, 'create']);

// Menyimpan order baru
Route::post('/order', [PrintingController::class, 'store']);

// Menampilkan daftar order
Route::get('/order', [PrintingController::class, 'orderList']);

// Menampilkan detail order
Route::get('/order/{id}', [PrintingController::class, 'orderDetail']);

Route::resource('order', OrderController::class);

Route::get('order/status/{status}', [OrderController::class, 'filterByStatus'])->name('order.status');
Route::get('order/search', [OrderController::class, 'search'])->name('order.search');

Route::resource('sale', SaleController::class);

Route::resource('finance', FinanceController::class);

Route::resource('spending', SpendingController::class);

Route::resource('invoice', InvoiceController::class);

Route::resource('product', ProductController::class);