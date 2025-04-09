<?php

namespace App\Actions\Books;

use Carbon\Carbon;
use App\Models\Book;
use App\Enums\BookType;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class GetTrendingBooks
{
    use AsAction;

    public function handle(): Collection
    {
        return Book::where('type', BookType::PHYSICAL)
            ->where('published_at', '>=', Carbon::now()->subMonth())
            ->get()->sortByDesc(function ($book) {
                return $book->getAvgRatingAttribute();
            })
            ->take(5)
            ->load('genres', 'series', 'authors');
    }
}
