<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

// Import Semua Controller
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/
// Route sementara untuk tes halaman 403
Route::get('/tes-403', function () {
    abort(403);
});

// Route sementara untuk tes halaman 500
Route::get('/tes-500', function () {
    abort(500);
});
// Halaman About Us (Tanpa Controller)
Route::view('/about', 'about')->name('about');

Route::get('/support', function () {
    return view('support'); 
})->name('support');

// Route untuk Setup Guide
Route::get('/setup-guide', function () {
    return view('setup-guide');
})->name('setup-guide');

Route::get('/returns', function () {
    return view('returns'); 
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
Route::post('/cart/{id}', [CartController::class, 'store'])->name('cart.add');       
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update'); 
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.remove'); 

Route::post('/chatbot', [ChatbotController::class, 'reply'])->middleware('throttle:20,1');
Route::post('/chatbot/products', [ChatbotController::class, 'getProductCards'])->middleware('throttle:20,1');

// Midtrans Webhook
Route::post('/webhook/midtrans', [MidtransController::class, 'webhook'])->name('midtrans.webhook');


/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES (Hanya untuk yang BELUM Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Register Route
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    // 🔥 LIMIT: 3x coba, blokir 5 menit (Cegah spam buat akun palsu)
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,5'); 

    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.verify');
    // 🔥 LIMIT: 5x coba, blokir 1 menit (Cegah Brute Force nebak OTP)
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.process')->middleware('throttle:5,1');
    // 🔥 LIMIT: 3x kirim, blokir 5 menit (Cegah spam SMS/Email)
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend')->middleware('throttle:3,5');

    // Login Route
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // 🔥 LIMIT: 5x coba, blokir 1 menit (Cegah Brute Force nebak Password)
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); 

    // --- LUPA PASSWORD (FORGOT PASSWORD) ---
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    // 🔥 LIMIT: 3x kirim, blokir 10 menit (Cegah spam Email)
    Route::post('/forgot-password/send-otp', [AuthController::class, 'sendResetOtp'])->name('password.email')->middleware('throttle:3,10');
    // 🔥 LIMIT: 5x coba, blokir 1 menit (Cegah Brute Force OTP Reset)
    Route::post('/forgot-password/verify', [AuthController::class, 'verifyResetOtp'])->name('password.verify')->middleware('throttle:5,1');

    // --- GANTI PASSWORD (RESET PASSWORD) ---
    Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update')->middleware('throttle:3,1');
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
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Riwayat Pesanan (History)
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.app');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

    Route::get('/support-history', [ContactController::class, 'index'])->name('support.history');

    // Mengirim pesan baru
    // 🔥 LIMIT: 2x kirim, blokir 5 menit (Cegah Spam Tiket Support)
    Route::post('/support/send', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:2,5');
    
    Route::get('/address-book', [AddressController::class, 'index'])->name('address.index');

    Route::post('/profile/address', [AddressController::class, 'store'])->name('address.store');
    Route::get('/address/create', [AddressController::class, 'create'])->name('address.create');
    Route::get('/address/{id}/edit', [AddressController::class, 'edit'])->name('address.edit');
    Route::put('/address/{id}', [AddressController::class, 'update'])->name('address.update');
    Route::delete('/address/{id}', [AddressController::class, 'destroy'])->name('address.destroy');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{id}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

     // Review
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/products/{slug}/reviews', [ProductController::class, 'reviews'])->name('products.reviews');

    // Coupon
    Route::post('/coupon/apply', [App\Http\Controllers\CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/coupon/remove', [App\Http\Controllers\CouponController::class, 'remove'])->name('coupon.remove');
});