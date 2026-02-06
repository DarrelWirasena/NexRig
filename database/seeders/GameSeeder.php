<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $games = [
            ['name' => 'Cyberpunk 2077', 'image_url' => 'https://placehold.co/100x100?text=CP2077'],
            ['name' => 'Call of Duty: Warzone', 'image_url' => 'https://placehold.co/100x100?text=COD'],
            ['name' => 'Valorant', 'image_url' => 'https://placehold.co/100x100?text=Valo'],
            ['name' => 'Fortnite', 'image_url' => 'https://placehold.co/100x100?text=Fortnite'],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}