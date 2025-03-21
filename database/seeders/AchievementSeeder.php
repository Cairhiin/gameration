<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Achievement::create([
            'title' => 'Novice Rater',
            'description' => 'Rate 1 game',
            'points' => 10,
            'image' => 'test.jpg'
        ]);

        Achievement::create([
            'title' => 'Casual Rater',
            'description' => 'Rate 10 games',
            'points' => 10,
            'image' => 'test.jpg'
        ]);

        Achievement::create([
            'title' => 'Skilled Rater',
            'description' => 'Rate 50 games',
            'points' => 10,
            'image' => 'test.jpg'
        ]);

        Achievement::create([
            'title' => 'Pro Rater',
            'description' => 'Rate 250 games',
            'points' => 10,
            'image' => 'test.jpg'
        ]);

        Achievement::create([
            'title' => 'Master Rater',
            'description' => 'Rate 1000 games',
            'points' => 10,
            'image' => 'test.jpg'
        ]);

        Achievement::create([
            'title' => 'Novice Reviewer',
            'description' => 'Review 1 game',
            'points' => 10,
            'image' => 'test.jpg'
        ]);

        Achievement::create([
            'title' => 'Casual Reviewer',
            'description' => 'Review 10 games',
            'points' => 10,
            'image' => 'test.jpg'
        ]);

        Achievement::create([
            'title' => 'Skilled Reviewer',
            'description' => 'Review 50 games',
            'points' => 10,
            'image' => 'test.jpg'
        ]);

        Achievement::create([
            'title' => 'Pro Reviewer',
            'description' => 'Review 250 games',
            'points' => 10,
            'image' => 'test.jpg'
        ]);

        Achievement::create([
            'title' => 'Master Reviewer',
            'description' => 'Review 1000 games',
            'points' => 10,
            'image' => 'test.jpg'
        ]);
    }
}
