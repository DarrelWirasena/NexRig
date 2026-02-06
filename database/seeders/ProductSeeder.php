<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori
        $catEntry = Category::create(['name' => 'Entry-Level', 'slug' => 'entry-level']);
        $catPro = Category::create(['name' => 'Professional', 'slug' => 'professional']);
        $catExtreme = Category::create(['name' => 'Extreme', 'slug' => 'extreme']);

        // 2. Buat Produk 1 (Entry Level)
        $p1 = Product::create([
            'category_id' => $catEntry->id,
            'name' => 'Nebula Starter',
            'slug' => 'nebula-starter',
            'price' => 999.00,
            'description' => 'Great for 1080p gaming & Esports titles.',
            'is_active' => true,
        ]);
        
        // Tambahkan Gambar untuk Produk 1
        ProductImage::create([
            'product_id' => $p1->id,
            'image_url' => 'https://placehold.co/600x400/png', // Gambar dummy
            'is_primary' => true
        ]);

        // 3. Buat Produk 2 (Professional)
        $p2 = Product::create([
            'category_id' => $catPro->id,
            'name' => 'Voyager Elite',
            'slug' => 'voyager-elite',
            'price' => 1899.00,
            'description' => '1440p High Refresh Rate gaming & streaming.',
            'is_active' => true,
        ]);

        ProductImage::create([
            'product_id' => $p2->id,
            'image_url' => 'https://placehold.co/600x400/png',
            'is_primary' => true
        ]);

        // ... Kamu bisa tambah produk lain sesuka hati
    }
}