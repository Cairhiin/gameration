<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Game;
use App\Models\User;
use App\Models\GameUser;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('en_US');

        for ($i = 0; $i < 50; $i++) {

            $user_id = User::inRandomOrder()->first()->id;
            $game_id = Game::inRandomOrder()->first()->id;

            GameUser::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'game_id' => $game_id,
                ],
                [
                    'content' => $faker->sentence(rand(3, 5)),
                    'approved' => rand(0, 1),
                ]
            );
        }
    }
}
