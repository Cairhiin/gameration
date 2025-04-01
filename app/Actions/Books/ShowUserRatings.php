<?php

namespace App\Actions\Books;

use App\Models\Game;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowUserRatings
{
    use AsAction;

    public function handle(Request $request, User $user): Response
    {
        $books = array();

        if ($request->has('sortBy')) {
            $books = Game::with('users')->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id)->orderBy('rating', 'desc');
            })->paginate(20);
        } else {
            $books = Game::with('users')->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id)->latest('book_user.created_at');
            })->paginate(20);
        }

        return Inertia::render('Games/User', [
            'books' => $books,
        ]);
    }
}
