<?php

use Illuminate\Support\Facades\Route;

// Import Semua Controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

// Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Katalog & Detail Produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Fitur Keranjang (Cart)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/add-to-cart/{id}', [CartController::class, 'store'])->name('cart.add');
Route::patch('/update-cart', [CartController::class, 'update'])->name('cart.update');
Route::get('/remove-from-cart/{id}', [CartController::class, 'destroy'])->name('cart.remove');


/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES (Hanya untuk yang BELUM Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


/*
|--------------------------------------------------------------------------
| 3. AUTH ROUTES (Hanya untuk yang SUDAH Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Proses Checkout (Simpan Order)
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Riwayat Pesanan (History)
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});