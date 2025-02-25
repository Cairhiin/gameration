<?php

namespace Database\Factories;

use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'user_id' => User::first()->id,
            'description' => $this->faker->paragraph(2),
            'developer_id' => Developer::factory()->create()->id,
            'publisher_id' => Publisher::factory()->create()->id,
            'released_at' => $this->faker->dateTime(),
        ];
    }
}
