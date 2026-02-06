<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


// Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Katalog (Shop)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Halaman Detail Produk (URL: /products/nebula-starter)
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');