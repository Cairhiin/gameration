<?php

namespace App\Actions\Books;

use App\Models\Game;
use App\Models\User;
use App\Enums\SystemMessage;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserRating
{
    use AsAction;

    /**
     * Updates the user's rating for a book. If the user has already rated the book,
     * the rating is updated with their new rating. If the user has not rated
     * the book before, a new rating is added to the book with a unique ID.
     *
     * @param Request $request The HTTP request containing the rating for the book.
     * @param Book $book The book to update the rating for.
     * @return float|null The updated rating for the book, or null if an error occurred.
     */
    public function handle(Request $request, Book $book): ?float
    {
        try {
            DB::beginTransaction();

            $bookUser = $book->users()->find(Auth::id());

            if ($bookUser) {
                $book->users()->updateExistingPivot(User::find(Auth::id())->id, ['rating' => $request->rating, 'updated_at' => now()]);
            } else {
                $book->users()->attach(User::find(Auth::id())->id, ['user_id' => Auth::id(), 'rating' => $request->rating, 'created_at' => now(), 'updated_at' => now()]);
            }

            $book->save();

            DB::commit();
        } catch (\Exception $e) {
            Log::debug($e);
            DB::rollBack();

            return null;
        }

        return $request->rating;
    }

    public function asController(Request $request, Book $book): RedirectResponse
    {
        $success = $this->handle($request, $book);

        if ($success) {
            return Redirect::route("books.show", $book->id)->with("message", "Rating" . SystemMessage::UPDATE_SUCCESS);
        } else {
            return Redirect::route("books.show", $book->id)->with("message", "Rating" . SystemMessage::UPDATE_FAILURE);
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('book:view');
    }
}
