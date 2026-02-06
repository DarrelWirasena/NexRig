<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman katalog (Semua Produk)
     */
    public function index(Request $request)
    {
        // Ambil data kategori untuk Sidebar Filter
        $categories = Category::all();

        // Query dasar: ambil produk yang aktif beserta gambar utamanya
        $query = Product::with(['category', 'images' => function($q) {
            $q->where('is_primary', true);
        }])->where('is_active', true);

        // FITUR FILTER: Jika ada request kategori di URL (?category=entry-level)
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Ambil hasil dengan pagination (misal 9 produk per halaman)
        $products = $query->latest()->paginate(9);

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan halaman detail produk berdasarkan Slug
     */
    public function show($slug)
    {
        // Eager loading semua relasi agar Frontend bisa menampilkan data lengkap
        $product = Product::with([
            'category', 
            'images', 
            'attributes', 
            'benchmarks.game', // Relasi nested: benchmark punya game
            'components'       // Relasi ke master hardware
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        return view('products.show', compact('product'));
    }
}