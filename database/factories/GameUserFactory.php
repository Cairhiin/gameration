<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
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
        return [
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->get()->first()->id,
            'game_id' => Game::all()->random()->id,
            'rating' => ($this->faker->randomDigit() + 1) / 2,
        ];
    }
}
