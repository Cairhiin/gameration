<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Publisher;
use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'published_at' => $this->faker->date,
            'image' => $this->faker->imageUrl,
            'ISBN' => $this->faker->isbn13,
            'pages' => $this->faker->numberBetween(100, 1200),
            'type' => $this->faker->randomElement(['paperback', 'audiobook', 'ebook']),
            'publisher_id' => Publisher::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'series_id' => Series::factory()->create()->id
        ];
    }
}
