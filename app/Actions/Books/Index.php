<?php

namespace App\Actions\Books;

use Carbon\Carbon;
use App\Models\Book;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Index
{
    use AsAction;

    public function handle(): Collection
    {
        $books = Book::latest()->limit(5)->get();

        return $books->load('series', 'authors');
    }

    public function asController(Request $request): Response
    {
        if (!$request->has('sortBy')) {
            return Inertia::render('Books/Index', [
                'genres' => GetAllGenres::run(),
                'books' => $this->handle(),
                'trendingBooks' => GetTrendingBooks::run(),
                'popularBooks' => GetPopularBooks::run(),
                'randomFriends' => GetRandomFriends::run(Auth::user()),
            ]);
        }

        $request->validate([
            'sortBy' => 'in:published_at,popular,avg_rating',
            'sortDirection' => 'in:asc,desc',
        ]);

        if ($request->sortBy === 'popular') {
            $books = $this->getByPopularity($request)->load('series', 'authors', 'genres');
        }

        if ($request->sortBy === 'avg_rating') {
            $books = $this->getByRating($request)->load('series', 'authors', 'genres');
        }

        if ($request->sortBy === 'published_at') {
            $books = $this->getByPublishingDate($request)->load('series', 'authors', 'genres');
        }

        if ($books) {
            /* Adding custom paginator for average rating sorting */
            $currentPage = $request->page ?? 1;
            $itemsPerPage = 15;
            $items = array_slice($books->toArray(), ($currentPage - 1) * $itemsPerPage, $itemsPerPage);
            $data = new LengthAwarePaginator($items, count($books->toArray()), $itemsPerPage, $currentPage, ['path' => url('books?sortBy=' . $request->sortBy . '&sortOrder=' . ($request->sortOrder ?? 'desc'))]);
        }

        return Inertia::render('Books/Sorted', [
            'books' => $data ?? $books,
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('book:viewAny');
    }

    public function getByPopularity(Request $request): Collection
    {
        $books = Cache::remember("booksByPopularity", now()->addMinutes(10), function () {
            return Book::whereYear('published_at', Carbon::now()->year)->get();
        });

        if ($request->sortOrder === 'asc') {
            return $books->sortBy(function ($book) {
                return $book->getAvgRatingAttribute();
            });
        }

        return $books->sortByDesc(function ($book) {
            return $book->getAvgRatingAttribute();
        });
    }

    public function getByPublishingDate(Request $request): Collection
    {
        $books = Cache::remember("books", now()->addMinutes(10), function () {
            return Book::all();
        });

        if ($request->sortOrder === 'asc') {
            return $books->sortBy(function ($book) {
                return $book->published_at;
            });
        }

        return $books->sortByDesc(function ($book) {
            return $book->published_at;
        });
    }

    public function getByRating(Request $request): Collection
    {
        $books = Cache::remember("books", now()->addMinutes(10), function () {
            return Book::all();
        });

        if ($request->sortOrder === 'asc') {
            return $books->sortBy(function ($book) {
                return $book->avg_rating;
            });
        }

        return $books->sortByDesc(function ($book) {
            return $book->avg_rating;
        });
    }
}
