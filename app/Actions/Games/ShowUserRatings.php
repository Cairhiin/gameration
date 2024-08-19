<?php

namespace App\Actions\Games;

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
        $games = array();

        if ($request->has('sortBy')) {
            $games = Game::with('users')->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id)->orderBy('rating', 'desc');
            })->paginate(20);
        } else {
            $games = Game::with('users')->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id)->latest('game_user.created_at');
            })->paginate(20);
        }

        return Inertia::render('Games/User', [
            'games' => $games,
        ]);
    }
}
