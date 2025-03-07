<?php

namespace App\Actions\Games;

use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        $user_rating = $this->handle($game);
        $game->user_ratings = ShowAllRatings::run($game->id);

        return Inertia::render('Games/Show', [
            'game' => $game->load('genres', 'developer', 'publisher'),
            'last_user_ratings' => fn() => ShowLastUserRatings::run($game->id),
            'rating' => $user_rating,
            'reviews' => ShowReviews::run($game),
            'user_review' => ShowUserReview::run($game),
        ]);
    }

    public function authorize(Game $game): bool
    {
        return Gate::allows('game:view', $game);
    }
}
