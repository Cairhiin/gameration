<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Achievement;
use App\Models\Book;
use App\Models\Person;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AchievementSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            DeveloperSeeder::class,
            PublisherSeeder::class,
            GenreSeeder::class,
            GameSeeder::class,
            PersonSeeder::class,
            BookSeeder::class,
            GameGenreSeeder::class,
            RatingSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
