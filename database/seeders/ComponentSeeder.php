<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Component;

class ComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            ['name' => 'Intel Core i3-13100F', 'type' => 'CPU', 'cost_price' => 1500000],
            ['name' => 'Intel Core i5-13600K', 'type' => 'CPU', 'cost_price' => 4500000],
            ['name' => 'Intel Core i9-14900KS', 'type' => 'CPU', 'cost_price' => 10000000],
            ['name' => 'AMD Ryzen 5 7600', 'type' => 'CPU', 'cost_price' => 3000000],
            ['name' => 'NVIDIA RTX 3060 12GB', 'type' => 'GPU', 'cost_price' => 4500000],
            ['name' => 'NVIDIA RTX 4070 Ti', 'type' => 'GPU', 'cost_price' => 12000000],
            ['name' => 'NVIDIA RTX 4090', 'type' => 'GPU', 'cost_price' => 28000000],
            ['name' => 'Kingston Fury 16GB DDR4', 'type' => 'RAM', 'cost_price' => 800000],
            ['name' => 'Corsair Vengeance 32GB DDR5', 'type' => 'RAM', 'cost_price' => 2500000],
            ['name' => 'Samsung 980 Pro 1TB', 'type' => 'Storage', 'cost_price' => 1800000],
            ['name' => 'NZXT H5 Flow', 'type' => 'Case', 'cost_price' => 1200000],
        ];

        foreach ($components as $comp) {
            Component::create($comp);
        }
    }
}