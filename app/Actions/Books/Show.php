<?php

namespace App\Actions\Books;

use App\Models\Book;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Actions\Books\ShowAllRatings;
use Lorisleiva\Actions\Concerns\AsAction;

class Show
{
    use AsAction;

    public function handle(Book $book): float
    {
        $bookUser = $book->users()->find(Auth::id());

        return $bookUser ? $bookUser->pivot->rating : 0.0;
    }

    public function asController(Book $book): Response
    {
        $user_rating = $this->handle($book);
        $book->user_ratings = ShowAllRatings::run($book->id);

        return Inertia::render('Books/Show', [
            'book' => $book->load('genres', 'series', 'publisher', 'authors', 'narrators'),
            'last_user_ratings' => fn() => ShowLastUserRatings::run($book->id),
            'rating' => $user_rating,
            'reviews' => ShowReviews::run($book),
            'user_review' => ShowUserReview::run($book),
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('book:view');
    }
}
