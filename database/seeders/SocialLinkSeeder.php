<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SocialLink;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialLink::insert([
            ['platform' => 'Instagram', 'url' => 'https://instagram.com/nexrig', 'is_active' => true, 'order' => 1],
            ['platform' => 'Twitter',   'url' => 'https://twitter.com/nexrig',   'is_active' => true, 'order' => 2],
            ['platform' => 'Youtube',   'url' => 'https://youtube.com/nexrig',   'is_active' => true, 'order' => 3],
        ]);
    }
}
