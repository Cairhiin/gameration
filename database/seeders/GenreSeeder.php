<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'type' => "both"
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
            'type' => "both"
        ]);

        DB::table('genres')->insert([
            'name' => "Casual",
            'type' => "both"
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

        DB::table('genres')->insert([
            'name' => "Biography",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Fantasy",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Mystery",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Business",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Children",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Crime",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Fiction",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "History",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Historical Fiction",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Horror",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Nonfiction",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Art",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Young Adult",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Romance",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Science Fiction",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Science",
            'type' => "book"
        ]);

        DB::table('genres')->insert([
            'name' => "Thriller",
            'type' => "book"
        ]);
    }
}
