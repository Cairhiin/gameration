<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Person;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::factory()->count(20)->create();

        foreach ($books as $book) {
            $book->authors()->attach(Person::where('type', 'author')->inRandomOrder()->first()->id);

            $book->genres()->attach(
                Genre::inRandomOrder()->take(2)->pluck('id')->toArray()
            );

            if ($book->type === 'audiobook') {
                $book->narrators()->attach(Person::where('type', 'narrator')->inRandomOrder()->first()->id);
            }
        }
    }
}
