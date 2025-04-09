<?php

namespace App\Actions\Books;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Book;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class Index
{
    use AsAction;

    public function handle(): Collection
    {
        $books = Book::latest()->limit(5)->get();

        return $books->load('series', 'authors');
    }

    public function asController(): Response
    {

        return Inertia::render('Books/Index', [
            'genres' => GetAllGenres::run(),
            'books' => $this->handle(),
            'trendingBooks' => GetTrendingBooks::run(),
            'popularBooks' => GetPopularBooks::run(),
            'randomFriends' => GetRandomFriends::run(Auth::user()),
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('book:viewAny');
    }
}
