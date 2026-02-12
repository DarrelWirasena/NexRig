<?php

namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. DATA PRODUK (Logika Lama + Optimasi)
        // Kita gunakan 'take(8)' agar query tidak mengambil ribuan produk jika database sudah besar.
        // Cukup ambil secukupnya untuk Slider & Bento Grid.
        $products = Product::with(['series.category', 'images'])
                    ->where('is_active', true)
                    ->latest()
                    ->take(8) 
                    ->get();

        // Ambil 4 pertama untuk Bento Grid
        $featured = $products->take(4);

        // 2. DATA HYPE BAR (Teks Berjalan)
        $hypes = [
            "High FPS Guarantee",
            "RTX 50-Series Ready",
            "Liquid Cooled",
            "24/7 Stress Tested",
            "Lifetime Support",
            "Zero Bloatware",
            "Professional Cable Management"
        ];

        // 3. DATA ARTIKEL TERBARU (Untuk Section 'NexRig Intel')
        $intelArticles = Article::where('status', 'published')->latest()->take(3)->get();

        // 4. KIRIM SEMUA KE VIEW
        return view('home', [
            'products' => $products,       // Untuk Slider (Section 4)
            'featured' => $featured,       // Untuk Bento Grid (Section 3)
            'hypes'    => $hypes,          // Untuk Running Text (Section 2)
            'intelArticles' => $intelArticles // Untuk NexRig Intel (Section 6)
        ]);
    }
}