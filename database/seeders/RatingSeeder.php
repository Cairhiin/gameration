<?php

namespace Database\Seeders;

use App\Models\GameUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GameUser::factory()
            ->count(200)
            ->create();
    }
}
