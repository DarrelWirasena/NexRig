<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSeries; // Kita panggil Series, bukan Category
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
        // Ambil semua Series yang sudah dibuat oleh CategorySeeder
        $allSeries = ProductSeries::with('category')->get();
        
        $components = Component::all();
        $games = Game::all();

        // LOOP 1: Loop per SERIES
        foreach ($allSeries as $series) {
            
            // Kita tentukan varian Tier untuk setiap Series
            $tiers = ['Core', 'Pro', 'Elite'];

            // LOOP 2: Buat 3 Varian (Core, Pro, Elite) per Series
            foreach ($tiers as $index => $tier) {
                
                $productName = "{$series->name} {$tier}"; // Contoh: Horizon Series Core
                
                // Logic Harga: Base price + (Index Series * 2jt) + (Index Tier * 3jt)
                $basePrice = 10000000 + ($series->id * 2000000) + ($index * 3000000);

                // 1. Create Product (Perhatikan kolomnya ganti jadi product_series_id)
                $product = Product::create([
                    'product_series_id' => $series->id, // <--- INI PENTING
                    'name' => $productName,
                    'slug' => Str::slug($productName),
                    'tier' => $tier, // Kolom baru yang kita bahas tadi
                    'price' => $basePrice,
                    'short_description' => "Varian {$tier} dari {$series->name}.",
                    'description' => "PC Rakitan {$series->category->name} kelas {$tier} dengan performa optimal.",
                    'is_active' => true,
                ]);

                // 2. Attach Images
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => 'https://placehold.co/600x400/101322/FFF?text=' . urlencode($tier),
                    'is_primary' => true,
                    'sort_order' => 1
                ]);

                // 3. Attach Components (Acak 4 biji)
                // Tips: Kalau mau realistis, Tier Elite harusnya dapet komponen mahal, tapi random dulu gpp
                $randomComponents = $components->random(4);
                foreach ($randomComponents as $comp) {
                    $product->components()->attach($comp->id, ['quantity' => 1]);
                }

                // 4. Attach Benchmarks
                $randomGames = $games->random(2);
                foreach ($randomGames as $game) {
                    $product->benchmarks()->attach($game->id, [
                        'resolution' => '1440p',
                        'avg_fps' => rand(60, 240)
                    ]);
                }

                // 5. Attributes
                ProductAttribute::create(['product_id' => $product->id, 'name' => 'Warranty', 'value' => '3 Years']);
                
                // 6. Intended Use
                IntendedUse::create([
                    'product_id' => $product->id, 
                    'title' => $series->category->name, // Ambil nama kategori induknya
                    'icon_url' => 'verified'
                ]);
            }
        }
    }
}