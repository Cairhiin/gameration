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
        $genres = array();

        if ($user) {
            $highestRatedGames = $user->game_user()->where('user_id', Auth::id())->orderBy('rating', 'desc')->take(5)->get();
            $latestRatedGames = $user->game_user()->where('user_id', Auth::id())->orderBy('updated_at', 'desc')->take(5)->get();
            $ratedGames = $user->game_user()->where('user_id', Auth::id())->get();

            foreach ($ratedGames as $ratedGame) {
                $genres = array_merge($genres, $ratedGame->load('game.genres')->game->genres->pluck('name')->toArray());
            }
        }

        return Inertia::render('Dashboard/Show', [
            'user' => $user,
            'latestRatedGames' => $latestRatedGames->load('game'),
            'highestRatedGames' => $highestRatedGames->load('game'),
            'favoriteGenres' => collect(array_count_values($genres))->sortDesc()->take(10)->all()
        ]);
    }
}
