<?php

namespace App\Actions\Users;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class Dashboard
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController(): Response
    {
        $user = User::with('role')->find(Auth::id());

        if ($user) {
            $highestRatedGames = $user->game_user()->where('user_id', Auth::id())->orderBy('rating', 'desc')->take(10)->get();
            $latestRatedGames = $user->game_user()->where('user_id', Auth::id())->orderBy('updated_at', 'desc')->take(10)->get();
            $ratedGames = $user->game_user()->where('user_id', Auth::id())->get();

            $genres = array();

            foreach ($ratedGames as $ratedGame) {
                foreach ($ratedGame->with('game.genres')->get() as $game) {
                    foreach ($game->game->genres->toArray() as $genre) {
                        $genres[] = $genre['name'];
                    };
                };
            }
        }

        return Inertia::render('Dashboard', [
            'user' => $user,
            'latestRatedGames' => $latestRatedGames->load('game'),
            'highestRatedGames' => $highestRatedGames->load('game'),
            'favoriteGenres' => array_count_values($genres),
        ]);
    }
}
