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
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;

// Redirect root ke dashboard jika sudah login, atau ke login jika belum
Route::get('/', function () {
    return Auth::check() ? redirect('dashboard') : redirect('login');
});

// Route untuk guest (yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route yang memerlukan authentication - SEMUA HARUS DIMASUKKAN DI SINI
Route::middleware('auth')->group(function () {
    // Route view
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/printing', function () {
        return view('printing');
    })->name('printing');

    Route::get('/product', function () {
        return view('product');
    })->name('product');

    Route::get('/order', function () {
        return view('order');
    })->name('order');

    Route::get('/sale', function () {
        return view('sale');
    })->name('sale');

    Route::get('/finance', function () {
        return view('finance');
    })->name('finance');

    Route::get('/spending', function () {
        return view('spending');
    })->name('spending');
    Route::get('/transaction', function () {
        return view('transaction');
    })->name('transaction');


    // Route resource controllers - SEMUA resource HARUS ada di dalam middleware
    Route::resource('dashboard', DashboardController::class);
    Route::resource('printing', PrintingController::class);
    Route::resource('order', OrderController::class);
    Route::resource('sale', SaleController::class);
    Route::get('/sale/product', [SaleController::class, 'product'])->name('sale.product');
    Route::resource('transaction', TransactionController::class);
    Route::patch('/transaction/{transaction}/mark-completed', [TransactionController::class, 'markCompleted'])->name('transaction.markCompleted');
    Route::post('/transaction/{transaction}/update-status', [TransactionController::class, 'updateStatus'])
        ->name('transaction.update-status');
    Route::patch('/transaction/{id}/mark-completed', [TransactionController::class, 'markCompleted'])
        ->name('transaction.mark-completed');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transaction.export');
    Route::resource('finance', FinanceController::class);
    Route::resource('spending', SpendingController::class);
    Route::resource('invoice', InvoiceController::class);
    Route::resource('product', ProductController::class);
    Route::resource('order', OrderController::class);
    Route::resource('purchase', PurchaseController::class);
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Fallback untuk menangani semua URL yang tidak terdaftar
Route::fallback(function () {
    return Auth::check()
        ? redirect('/dashboard')->with('error', 'Halaman tidak ditemukan.')
        : redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
});
