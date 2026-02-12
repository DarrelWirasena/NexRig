<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSeries;
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
        $allSeries = ProductSeries::with('category')->get();
        $components = Component::all();
        $games = Game::all();

        foreach ($allSeries as $series) {
            $tiers = ['Core', 'Pro', 'Elite'];

            foreach ($tiers as $index => $tier) {
                $productName = "{$series->name} {$tier}";
                $basePrice = 10000000 + ($series->id * 2000000) + ($index * 3000000);

                // 1. Create Product
                $product = Product::create([
                    'product_series_id' => $series->id,
                    'name' => $productName,
                    'slug' => Str::slug($productName),
                    'tier' => $tier,
                    'price' => $basePrice,
                    'short_description' => "Varian {$tier} dari {$series->name}.",
                    'description' => "The {$series->name} {$tier} features a PC that is fine-tuned for the performance needed for a competitive edge. Ideal for higher resolution or higher frame rates.",
                    'is_active' => true,
                ]);

                // 2. Attach Images
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?q=80&w=1000',
                    'is_primary' => true,
                    'sort_order' => 1
                ]);

                // 3. Attach Components
                $randomComponents = $components->random(min(4, $components->count()));
                foreach ($randomComponents as $comp) {
                    $product->components()->attach($comp->id, ['quantity' => 1]);
                }

                // 4. Attach Benchmarks
                $resolutions = ['1080p', '1440p', '4k'];
                foreach ($resolutions as $res) {
                    $randomGames = $games->random(min(2, $games->count()));
                    foreach ($randomGames as $game) {
                        $product->benchmarks()->attach($game->id, [
                            'resolution' => $res,
                            'avg_fps' => ($res == '4k') ? rand(60, 100) : (($res == '1440p') ? rand(100, 160) : rand(160, 300))
                        ]);
                    }
                }

                // 5. Attributes
                ProductAttribute::create(['product_id' => $product->id, 'name' => 'Warranty', 'value' => '3 Years Full Parts']);
                
                // 6. [MODIFIKASI] Starforge Style Intended Use
                // Kita buatkan 4 poin wajib untuk setiap PC
                $intendedData = [
                    [
                        'title' => 'Esports Gaming',
                        'icon' => 'sports_esports',
                        'desc' => "Designed for high FPS in competitive titles. When you're ready to compete, this {$tier} rig is ready for you."
                    ],
                    [
                        'title' => 'Work And Play',
                        'icon' => 'terminal',
                        'desc' => "Balanced for professional creative work and enthusiast gaming. Perfect for multitasking."
                    ],
                    [
                        'title' => 'Cinematic Visuals',
                        'icon' => 'movie',
                        'desc' => "Tuned for ray tracing and high-fidelity textures. Experience games exactly as developers intended."
                    ],
                    [
                        'title' => 'Built to Last',
                        'icon' => 'verified',
                        'desc' => "Only high-end components are used, ensuring your {$series->name} system is ready for the future."
                    ]
                ];

                foreach ($intendedData as $data) {
                        $use = IntendedUse::firstOrCreate(
                        ['title' => $data['title']],
                        ['icon_url' => $data['icon'], 'description' => $data['desc']]
                    );

                    // Hubungkan ke produk melalui tabel PIVOT
                    $product->intendedUses()->attach($use->id);
                    
                }
            }
        }
    }
}