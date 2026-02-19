<?php

namespace Database\Seeders;

use App\Models\QuickFilter;
use Illuminate\Database\Seeder;

class QuickFilterSeeder extends Seeder
{
    public function run()
    {
        $filters = [
            ['keyword' => 'RTX 4090', 'order' => 1],
            ['keyword' => 'RTX 4080', 'order' => 2],
            ['keyword' => 'Intel i9', 'order' => 3],
            ['keyword' => 'Ryzen 9', 'order' => 4],
            ['keyword' => 'White Build', 'order' => 5],
            ['keyword' => 'Mini ITX', 'order' => 6],
        ];

        foreach ($filters as $filter) {
            QuickFilter::create([
                'keyword' => $filter['keyword'],
                'is_active' => true,
                'order' => $filter['order']
            ]);
        }
    }
}