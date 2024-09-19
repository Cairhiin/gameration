<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GameGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = Game::all();

        foreach ($games as $game) {
            DB::table('game_genre')->insert([
                'game_id' => $game->id,
                'genre_id' => Genre::all()->random()->id
            ]);
        }
    }
}
