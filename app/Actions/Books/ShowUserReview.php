<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Models\BookUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowUserReview
{
    use AsAction;

    public function handle(Book $book): ?BookUser
    {
        return BookUser::where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->join('users', 'book_user.user_id', '=', 'users.id')
            ->select('book_user.*', 'users.username')
            ->first();
    }

    public function asController(Book $book): ?BookUser
    {
        return $this->handle($book);
    }

    public function authorize(): bool
    {
        return Gate::allows('review:view');
    }
}
