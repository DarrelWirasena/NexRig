<?php

use Illuminate\Support\Facades\Route;

// Import Semua Controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

// Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman About Us (Tanpa Controller)
Route::view('/about', 'about')->name('about');

// Halaman Katalog & Detail Produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Fitur Keranjang (Cart)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Ubah dari GET ke POST, dan sesuaikan nama URL-nya
Route::post('/add-to-cart/{id}', [CartController::class, 'store'])->name('cart.add');
Route::patch('/update-cart', [CartController::class, 'update'])->name('cart.update');
Route::get('/remove-from-cart/{id}', [CartController::class, 'destroy'])->name('cart.remove');


/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES (Hanya untuk yang BELUM Login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Register Route
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register'); // Menampilkan form
    Route::post('/register', [AuthController::class, 'register']); // Memproses data form
    
    // Login Route
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Menampilkan form
    Route::post('/login', [AuthController::class, 'login']); // Memproses login

 
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
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    // Route Halaman Success (Letakkan di bawah route checkout store)
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Riwayat Pesanan (History)
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');

       // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.app');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/address', [ProfileController::class, 'address'])->name('profile.address');

    // Route untuk menampilkan form Tambah
    Route::get('/profile/address/create', [ProfileController::class, 'createAddress'])->name('address.create');
    // Route untuk Proses Simpan (Store)
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('address.store');
    // Route untuk menampilkan form Edit (dengan ID)
    Route::get('/profile/address/{id}/edit', [ProfileController::class, 'editAddress'])->name('address.edit');
    // Route untuk Proses Update (Update)
    Route::put('/profile/address/{id}', [ProfileController::class, 'updateAddress'])->name('address.update');
});