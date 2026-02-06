<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // LOGIKA: Ambil semua produk yang statusnya 'is_active' = true
        // PENTING: Kita load 'category' DAN 'images' sekaligus.
        $products = Product::with(['category', 'images'])
                    ->where('is_active', true)
                    ->latest() // Urutkan dari yang paling baru diinput
                    ->get();

        // (Opsional) Kalau mau ambil 3 produk teratas khusus untuk slider/hero
        // Kita ambil dari koleksi $products di atas biar gak query ulang ke DB
        $featured = $products->take(3);

        return view('home', [
            'products' => $products,
            'featured' => $featured
        ]);
    }
}