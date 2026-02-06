<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;


// Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Katalog (Shop)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Halaman Detail Produk (URL: /products/nebula-starter)
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Route Keranjang
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/add-to-cart/{id}', [CartController::class, 'store'])->name('cart.add');
Route::get('/remove-from-cart/{id}', [CartController::class, 'destroy'])->name('cart.remove');