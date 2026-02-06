<?php

// routes/web.php
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
// Tambahkan route ini agar link detail tidak error
Route::get('/products/{slug}', [HomeController::class, 'show'])->name('products.show');
