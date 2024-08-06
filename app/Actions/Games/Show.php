<?php

namespace App\Actions\Games;

use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class Show
{
    use AsAction;

    public function handle(Game $game): float
    {
        $gameUser = $game->users()->find(Auth::id());

        return $gameUser ? $gameUser->pivot->rating : 0.0;
    }

    public function asController(Game $game): Response
    {
        $rating = $this->handle($game);

        return Inertia::render('Games/Show', [
            'game' => $game->load('genres', 'developer', 'publisher'),
            'rating' => $rating
        ]);
    }
}
