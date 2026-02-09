<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\ProductSeries; // Jangan lupa import ini!
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Data Struktur: Kategori => Daftar Series-nya
        $data = [
            'Gaming PC' => [
                'Horizon Series', // Entry/Mid
                'Voyager Series', // High End
                'Navigator Series' // Extreme
            ],
            'Workstation' => [
                'Creator Series',
                'Architect Series'
            ],
            'Streaming' => [
                'Canvas Series',
                'Broadcast Series'
            ],
            'Laptops' => [
                'Portable One',
                'Portable Pro'
            ]
        ];

        foreach ($data as $categoryName => $seriesList) {
            // 1. Buat Kategori
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName)
            ]);

            // 2. Buat Series untuk Kategori tersebut
            foreach ($seriesList as $seriesName) {
                ProductSeries::create([
                    'category_id' => $category->id,
                    'name' => $seriesName,
                    'slug' => Str::slug($seriesName),
                    'description' => "Seri {$seriesName} terbaik untuk kebutuhan {$categoryName}."
                ]);
            }
        }
    }
}