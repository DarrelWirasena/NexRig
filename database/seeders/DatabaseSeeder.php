<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // PENTING: Urutan tidak boleh tertukar karena ada Foreign Key
        $this->call([
            UserSeeder::class,      // Bikin User dulu
            CategorySeeder::class,  // Bikin Kategori
            ComponentSeeder::class, // Bikin Gudang Komponen
            GameSeeder::class,      // Bikin Master Game
            ProductSeeder::class,   // Bikin Produk (Menggunakan data dari Kategori, Komponen, & Game)
        ]);
    }
}