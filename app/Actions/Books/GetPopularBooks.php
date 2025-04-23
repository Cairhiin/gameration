<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Enums\BookType;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class GetPopularBooks
{
    use AsAction;

    public function handle(): Collection
    {
        return $this->getPopularBooks()
            ->load('genres', 'series', 'authors');
    }

    public function getPopularBooks(float $number = 4.0): ?Collection
    {
        $books = Book::where('type', BookType::PHYSICAL)
            ->get()->filter(function ($book) use ($number) {
                return $book->getAvgRatingAttribute() >= $number;
            });

        if ($books->count() >= 5) {
            return $books->random(5);
        }

        if ($number - 0.5 < 2.5) {
            return $books;
        }

        // If there are not enough books with the given rating, try again with a lower rating
        return $this->getPopularBooks($number - 0.5);
    }
}
