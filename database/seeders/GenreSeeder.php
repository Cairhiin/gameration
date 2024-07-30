<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            'name' => "ARPG",
        ]);

        DB::table('genres')->insert([
            'name' => "RPG",
        ]);

        DB::table('genres')->insert([
            'name' => "Sports",
        ]);

        DB::table('genres')->insert([
            'name' => "Simulation",
        ]);

        DB::table('genres')->insert([
            'name' => "Platformer",
        ]);

        DB::table('genres')->insert([
            'name' => "Survival",
        ]);

        DB::table('genres')->insert([
            'name' => "Battle Royale",
        ]);

        DB::table('genres')->insert([
            'name' => "MMO",
        ]);

        DB::table('genres')->insert([
            'name' => "RTS",
        ]);

        DB::table('genres')->insert([
            'name' => "Fighting",
        ]);

        DB::table('genres')->insert([
            'name' => "Puzzle",
        ]);

        DB::table('genres')->insert([
            'name' => "Racing",
        ]);

        DB::table('genres')->insert([
            'name' => "Casual",
        ]);

        DB::table('genres')->insert([
            'name' => "MOBA",
        ]);

        DB::table('genres')->insert([
            'name' => "Tower Defense",
        ]);

        DB::table('genres')->insert([
            'name' => "Bullet Hell",
        ]);

        DB::table('genres')->insert([
            'name' => "4X",
        ]);

        DB::table('genres')->insert([
            'name' => "Soulslike",
        ]);

        DB::table('genres')->insert([
            'name' => "Roguelike",
        ]);

        DB::table('genres')->insert([
            'name' => "JRPG",
        ]);

        DB::table('genres')->insert([
            'name' => "Open World",
        ]);
    }
}
