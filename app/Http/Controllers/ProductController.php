<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Halaman Katalog (Semua Produk)
    public function index()
    {
        $products = Product::with(['category', 'images'])
                    ->where('is_active', true)
                    ->latest()
                    ->paginate(9); // Pakai pagination biar rapi (9 produk per halaman)

        return view('products.index', [
            'products' => $products
        ]);
    }

    // Halaman Detail Satu Produk
    // Kita cari berdasarkan 'slug' (URL cantik), bukan ID
    public function show($slug)
    {
        // Ambil produk beserta SEMUA relasinya (Eager Loading Komplit)
        $product = Product::with([
                        'category', 
                        'images', 
                        'components',           // Untuk list hardware
                        'benchmarks.game',      // Untuk grafik FPS
                        'intendedUses',         // Untuk fitur ikon
                        'attributes'            // Untuk spek lain
                    ])
                    ->where('slug', $slug)
                    ->where('is_active', true)
                    ->firstOrFail(); // Kalau gak ketemu, otomatis 404 Not Found

        return view('products.show', [
            'product' => $product
        ]);
    }
}