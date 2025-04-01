<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Models\BookUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Pagination\LengthAwarePaginator;

class ShowReviews
{
    use AsAction;

    public function handle(Book $book): LengthAwarePaginator
    {
        $review = BookUser::where('book_id', $book->id)
            ->whereNot('user_id', Auth::id())
            ->whereNotNull('content')
            ->join('users', 'book_user.user_id', '=', 'users.id')
            ->select('book_user.*', 'users.username');

        $user = getAuthUser();

        if ($user->isAdmin() || $user->isModerator()) {
            return $review->orderBy('book_user.created_at', 'desc')->paginate(10);
        }

        return $review->where('approved', '1')
            ->orderBy('book_user.created_at', 'desc')
            ->paginate(10);
    }

    public function asController(Book $book): LengthAwarePaginator
    {
        return $this->handle($book);
    }

    public function authorize(): bool
    {
        return Gate::allows('review:viewAny');
    }
}
