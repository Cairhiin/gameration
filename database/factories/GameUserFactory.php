<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GameUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameUser>
 */
class GameUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_id = User::inRandomOrder()->first()->id;
        $game_id = Game::inRandomOrder()->first()->id;

        while (GameUser::where('game_id', $game_id)->where('user_id', $user_id)->exists()) {
            $user_id = User::inRandomOrder()->first()->id;
            $game_id = Game::inRandomOrder()->first()->id;
        }

        return [
            'user_id' => $user_id,
            'game_id' => $game_id,
            'rating' => ($this->faker->randomDigit() + 1) / 2,
            'content' => $this->faker->paragraph(2),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
