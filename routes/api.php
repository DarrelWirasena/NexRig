<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes  (/api prefix ditangani otomatis oleh RouteServiceProvider)
|--------------------------------------------------------------------------
| Semua route di sini otomatis memiliki prefix /api
| Contoh: Route::get('user') → accessible di /api/user
|
| Response Format (dari ApiController):
| {
|   "success": true|false,
|   "message": "...",
|   "data": {...} | null,
|   "errors": {...}    ← hanya saat validasi gagal
| }
*/

// ──────────────────────────────────────────────────────
// PUBLIC ROUTES (tanpa auth)
// ──────────────────────────────────────────────────────
Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('register',   [Api\AuthController::class, 'register'])->name('register');
    Route::post('login',      [Api\AuthController::class, 'login'])->name('login');
    Route::post('verify-otp', [Api\AuthController::class, 'verifyOtp'])->name('verify-otp');
});

// Midtrans Webhook (tanpa auth, tapi ada signature verification di service)
Route::post('midtrans/webhook', [Api\MidtransController::class, 'webhook'])->name('api.midtrans.webhook');

// ──────────────────────────────────────────────────────
// PROTECTED ROUTES (memerlukan auth:sanctum)
// ──────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('auth/logout', [Api\AuthController::class, 'logout'])->name('api.auth.logout');

    // Addresses
    Route::prefix('addresses')->name('api.addresses.')->group(function () {
        Route::get('/',                [Api\AddressController::class, 'index'])->name('index');
        Route::post('/',               [Api\AddressController::class, 'store'])->name('store');
        Route::put('{id}',             [Api\AddressController::class, 'update'])->name('update');
        Route::delete('{id}',          [Api\AddressController::class, 'destroy'])->name('destroy');
        Route::patch('{id}/default',   [Api\AddressController::class, 'setDefault'])->name('set-default');
    });

    // Cart
    Route::prefix('cart')->name('api.cart.')->group(function () {
        Route::get('/',        [Api\CartController::class, 'index'])->name('index');
        Route::post('checkout',[Api\CartController::class, 'checkout'])->name('checkout');
    });

    // Orders
    Route::prefix('orders')->name('api.orders.')->group(function () {
        Route::post('/',     [Api\OrderController::class, 'createOrder'])->name('create');
        Route::get('{id}',   [Api\OrderController::class, 'getOrderDetails'])->name('show');
    });

    // Contact
    Route::prefix('contact')->name('api.contact.')->group(function () {
        Route::get('/',  [Api\ContactController::class, 'index'])->name('index');
        Route::post('/', [Api\ContactController::class, 'store'])->name('store');
    });

    // Payment
    Route::prefix('payments')->name('api.payments.')->group(function () {
        Route::post('{orderId}/process', [Api\MidtransController::class, 'processPayment'])->name('process');
    });
});