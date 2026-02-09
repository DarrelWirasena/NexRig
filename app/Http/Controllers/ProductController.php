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
        // 1. Ambil data kategori untuk Sidebar Filter
        $categories = Category::with('series')->get();

        // 2. Query dasar: Siapkan query, tapi jangan di-get() dulu
        // Kita gunakan eager loading 'series.category' sesuai struktur baru
        $query = Product::with(['series.category', 'images' => function($q) {
            $q->where('is_primary', true);
        }])->where('is_active', true);

        // 3. FILTER CATEGORY: Jika ada request kategori di URL (?category=entry-level)
        if ($request->has('category')) {
            $query->whereHas('series.category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. FILTER SEARCH: Jika ada request search di URL (?search=intel)
        // Bagian ini disisipkan di sini, sebelum pagination
        if ($request->filled('search')) {
            $search = $request->search;
            
            // Kita gunakan where function agar logika OR dikurung dalam tanda kurung ( )
            // Contoh SQL: WHERE is_active = 1 AND (name LIKE %intel% OR description LIKE %intel%)
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  // Opsional: Cari juga berdasarkan nama series
                  ->orWhereHas('series', function($qSeries) use ($search) {
                      $qSeries->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 5. Eksekusi Query dengan Pagination
        $products = $query->latest()->paginate(9);

        // PENTING: Tambahkan ini agar saat pindah halaman 2, filter search/category tidak hilang
        $products->appends($request->all());

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan halaman detail produk berdasarkan Slug
     */
    public function show($slug)
    {
        // Tidak ada perubahan di sini, kode lama kamu sudah benar
        $product = Product::with([
            'series.category', 
            'images', 
            'attributes', 
            'benchmarks', 
            'components',
            'intendedUses'
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        return view('products.show', compact('product'));
    }
}