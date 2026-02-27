<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

// Import Semua Controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

Route::get('/debug-headers', function () {
    return response()->json([
        'https' => request()->isSecure(),
        'scheme' => request()->getScheme(),
        'forwarded_proto' => request()->header('X-Forwarded-Proto'),
        'all_headers' => request()->headers->all(),
    ]);
});

// Halaman About Us (Tanpa Controller)
Route::view('/about', 'about')->name('about');

Route::get('/support', function () {
    return view('support'); // pastikan nama file view-nya support.blade.php
})->name('support');

// Route untuk Setup Guide
// Pastikan nama file Anda adalah: resources/views/setup-guide.blade.php
Route::get('/setup-guide', function () {
    return view('setup-guide'); 
})->name('setup-guide');

Route::get('/returns', function () {
    return view('returns'); // Terus panggil nama fail tanpa 'policies.'
})->name('returns');

Route::get('/privacy-policy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/warranty', function () {
    return view('warranty');
})->name('warranty');

// Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route untuk halaman daftar artikel (Index)
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
// Route untuk detail artikel (Show)
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

// Halaman Katalog & Detail Produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Fitur Keranjang (Cart)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// [DIPERBARUI] Menggunakan URL Standar RESTful
Route::post('/cart/{id}', [CartController::class, 'store'])->name('cart.add');       // URL lebih bersih
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update'); // URL update quantity
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.remove'); // [PENTING] Method DELETE

Route::post('/chatbot', [App\Http\Controllers\ChatbotController::class, 'reply']);
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

    Route::patch('/address/{id}/default', [AddressController::class, 'setDefault'])->name('address.set_default');

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
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

       // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.app');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');


    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])
     ->name('orders.invoice');

    Route::get('/support-history', [ContactController::class, 'index'])->name('support.history');
    
    // Mengirim pesan baru
    Route::post('/support/send', [ContactController::class, 'store'])->name('contact.store');   
    Route::get('/address-book', [AddressController::class, 'index'])->name('address.index');

    // 2. Route untuk Simpan Data (Metode POST)
    // Pastikan URL ini yang digunakan di <form action="...">
    Route::post('/profile/address', [AddressController::class, 'store'])->name('address.store');
    
    // Route lainnya...
    Route::get('/address/create', [AddressController::class, 'create'])->name('address.create');
    Route::get('/address/{id}/edit', [AddressController::class, 'edit'])->name('address.edit');
    Route::put('/address/{id}', [AddressController::class, 'update'])->name('address.update');
    Route::delete('/address/{id}', [AddressController::class, 'destroy'])->name('address.destroy');
});