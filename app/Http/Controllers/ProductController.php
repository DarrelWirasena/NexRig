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

        // 4. FILTER SEARCH (SMART SEARCH)
        if ($request->filled('search')) {
            // Pecah kalimat menjadi array kata-kata
            // Contoh: "Intel i3" menjadi ['Intel', 'i3']
            $keywords = explode(' ', $request->search);

            $query->where(function($q) use ($keywords) {
                
                // A. Cari di Nama Produk (Harus mengandung semua kata kunci)
                // Misal: Cari "NexRig White", akan ketemu "NexRig Ultra White"
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

                // C. ATAU Cari di Komponen (Ini solusi masalahmu!)
                // Logic: Cari produk yang punya komponen, dimana komponen itu mengandung "Intel" DAN "i3"
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

        return view('products.show', compact('product'));
    }
}