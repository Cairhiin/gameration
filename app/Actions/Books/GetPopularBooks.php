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
        return Book::where('type', BookType::PHYSICAL)
            ->get()->filter(function ($book) {
                return $book->getAvgRatingAttribute() >= 3.0;
            })
            ->take(5)
            ->load('genres', 'series', 'authors');
    }
}
