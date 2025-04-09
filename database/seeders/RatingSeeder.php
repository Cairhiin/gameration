<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookUser;
use App\Models\Game;
use App\Models\User;
use App\Models\GameUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*  GameUser::factory()
            ->count(200)
            ->create(); */

        for ($i = 0; $i < 200; $i++) {

            $user_id = User::inRandomOrder()->first()->id;
            $game_id = Game::inRandomOrder()->first()->id;
            $book_id = Book::inRandomOrder()->first()->id;

            GameUser::updateOrCreate([
                'user_id' => $user_id,
                'game_id' => $game_id,
                'rating' => (random_int(5, 10)) / 2,
            ]);

            BookUser::updateOrCreate([
                'user_id' => $user_id,
                'book_id' => $book_id,
                'rating' => (random_int(5, 10)) / 2,
            ]);
        }
    }
}
