<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. DATA PRODUK — filter produk yang stoknya tersedia
        // scopeInStock(): track_stock=false (unlimited) ATAU stock > 0
        $products = Product::with(['series.category', 'images'])
            ->where('is_active', true)
            ->inStock()   // ← hanya produk yang stok tersedia
            ->latest()
            ->take(8)
            ->get();

        // Ambil 4 pertama untuk Bento Grid
        $featured = $products->take(4);

        // 2. DATA HYPE BAR
        $hypes = [
            "High FPS Guarantee",
            "RTX 50-Series Ready",
            "Liquid Cooled",
            "24/7 Stress Tested",
            "Lifetime Support",
            "Zero Bloatware",
            "Professional Cable Management",
        ];

        // 3. DATA ARTIKEL TERBARU
        $intelArticles = Article::where('status', 'published')->latest()->take(3)->get();

        return view('home', [
            'products'      => $products,
            'featured'      => $featured,
            'hypes'         => $hypes,
            'intelArticles' => $intelArticles,
        ]);
    }
}