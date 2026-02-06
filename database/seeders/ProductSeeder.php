<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Component;
use App\Models\Game;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\IntendedUse;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua kategori yang sudah dibuat tadi
        $categories = Category::all();
        
        // Ambil semua komponen & game buat di-random
        $components = Component::all();
        $games = Game::all();

        // LOOP 1: Untuk setiap Kategori (Ada 6)
        foreach ($categories as $category) {
            
            // LOOP 2: Buat 3 Produk per Kategori
            for ($i = 1; $i <= 3; $i++) {
                
                // Bikin nama produk unik, misal: "NexRig Entry Level - Tier 1"
                $productName = "NexRig {$category->name} - Tier {$i}";
                $basePrice = 10000000 + ($category->id * 5000000) + ($i * 2000000); // Rumus harga ngasal biar variatif

                // 1. Create Product
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $productName,
                    'slug' => Str::slug($productName),
                    'price' => $basePrice,
                    'short_description' => "PC rakitan terbaik untuk kategori {$category->name}.",
                    'description' => "Nikmati performa maksimal dengan {$productName}. Dirakit profesional dan ditest ketat.",
                    'is_active' => true,
                ]);

                // 2. Attach Images (1 Utama + 2 Tambahan)
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => 'https://placehold.co/600x400/101322/FFF?text=' . urlencode($productName),
                    'is_primary' => true,
                    'sort_order' => 1
                ]);
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => 'https://placehold.co/600x400/232948/FFF?text=Side+View',
                    'is_primary' => false,
                    'sort_order' => 2
                ]);

                // 3. Attach Components (Ambil 4 komponen acak dari gudang)
                // Kita pakai random components biar cepat
                $randomComponents = $components->random(4);
                foreach ($randomComponents as $comp) {
                    $product->components()->attach($comp->id, ['quantity' => 1]);
                }

                // 4. Attach Benchmarks (Ambil 2 game acak)
                $randomGames = $games->random(2);
                foreach ($randomGames as $game) {
                    $product->benchmarks()->attach($game->id, [
                        'resolution' => '1440p',
                        'avg_fps' => rand(60, 240) // Random FPS antara 60-240
                    ]);
                }

                // 5. Create Attributes (Spesifikasi Lain)
                ProductAttribute::create(['product_id' => $product->id, 'name' => 'Warranty', 'value' => '2 Years Official']);
                ProductAttribute::create(['product_id' => $product->id, 'name' => 'OS', 'value' => 'Windows 11 Pro']);

                // 6. Intended Uses (Kegunaan)
                IntendedUse::create([
                    'product_id' => $product->id, 
                    'title' => 'Gaming Ready', 
                    'icon_url' => 'sports_esports' // Nama icon material symbols
                ]);
            }
        }
    }
}