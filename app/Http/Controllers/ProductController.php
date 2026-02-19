<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\QuickFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman katalog (Semua Produk)
     */
    public function index(Request $request)
    {
        // Ambil data kategori untuk Sidebar Filter
        $categories = Category::with('series')->get();

        // Query dasar
        $query = Product::with(['series.category', 'images' => function($q) {
            $q->where('is_primary', true);
        }])->where('is_active', true);

        $categoryName = null;
        $searchKeyword = $request->search;

        // ==========================================
        // 1. FILTER CATEGORY
        // ==========================================
        if ($request->filled('category')) {
            $query->whereHas('series.category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
            $categoryName = ucwords(str_replace('-', ' ', $request->category));
        }

        // ==========================================
        // 2. FILTER SEARCH (SMART SEARCH)
        // ==========================================
        if ($request->filled('search')) {
            $keywords = explode(' ', $searchKeyword);

            $query->where(function($q) use ($keywords) {
                // A. Cari di Nama Produk
                $q->where(function($subQ) use ($keywords) {
                    foreach ($keywords as $word) {
                        $subQ->where('name', 'like', "%{$word}%");
                    }
                })
                // B. ATAU Cari di Deskripsi
                ->orWhere(function($subQ) use ($keywords) {
                    foreach ($keywords as $word) {
                        $subQ->where('description', 'like', "%{$word}%");
                    }
                })
                // C. ATAU Cari di Komponen
                ->orWhereHas('components', function($qComp) use ($keywords) {
                    $qComp->where(function($deepQ) use ($keywords) {
                        foreach ($keywords as $word) {
                            $deepQ->where('name', 'like', "%{$word}%");
                        }
                    });
                })
                // D. ATAU Cari di Series
                ->orWhereHas('series', function($qSeries) use ($keywords) {
                    $qSeries->where(function($deepQ) use ($keywords) {
                        foreach ($keywords as $word) {
                            $deepQ->where('name', 'like', "%{$word}%");
                        }
                    });
                });
            });
        }

        // ==========================================
        // 3. FILTER PRICE RANGE (BARU!)
        // ==========================================
        if ($request->filled('price')) {
            switch ($request->price) {
                case 'under-20':
                    $query->where('price', '<', 20000000); // Di bawah 20 Juta
                    break;
                case '20-50':
                    $query->whereBetween('price', [20000000, 50000000]); // 20 - 50 Juta
                    break;
                case 'over-50':
                    $query->where('price', '>', 50000000); // Di atas 50 Juta
                    break;
            }
        }

        // ==========================================
        // 4. SORTING (BARU!)
        // ==========================================
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc'); // Harga Termurah
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc'); // Harga Termahal
                    break;
                case 'newest':
                default:
                    $query->latest(); // Terbaru
                    break;
            }
        } else {
            // Default sorting jika user belum memilih apapun
            $query->latest();
        }

        // ==========================================
        // 5. LOGIKA TITLE DINAMIS
        // ==========================================
        if ($categoryName && $searchKeyword) {
            $title = 'Search: "' . $searchKeyword . '" in ' . $categoryName;
        } elseif ($categoryName) {
            $title = $categoryName;
        } elseif ($searchKeyword) {
            $title = 'Search: "' . $searchKeyword . '"';
        } else {
            $title = 'Product Catalog';
        }

        // ==========================================
        // 6. EKSEKUSI QUERY
        // ==========================================
        // PERHATIKAN: Saya menghapus ->latest() dari sini karena sudah ditangani di logika Sort di atas
        $products = $query->paginate(9);

        // Bawa semua parameter URL (termasuk price dan sort) ke halaman pagination selanjutnya
        $products->appends($request->all());

        // ==========================================
        // 7. DATA CHIPS (DARI DATABASE + CACHE)
        // ==========================================
        // Ingat 'quick_filters_cache' selama 24 jam (1440 menit)
        $chips = Cache::remember('quick_filters_cache', 1440, function () {
            return QuickFilter::where('is_active', true)
                ->orderBy('order', 'asc')
                ->pluck('keyword');
        });

        // Pastikan variabel $chips ikut dikirim ke view
        return view('products.index', compact('products', 'categories', 'title', 'chips'));
    }

    

    /**
     * Menampilkan halaman detail produk berdasarkan Slug
     */
    public function show($slug)
    {
        // Tidak ada perubahan di sini, kode lama kamu sudah benar
        $product = Product::with([
            'series.category', 
            'series.products',
            'images', 
            'attributes', 
            'benchmarks', 
            'components',
            'intendedUses'
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();
        $title = $product->name ;
        return view('products.show', compact('product', 'title'));
    }
}