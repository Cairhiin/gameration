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
        $user = User::with('role', 'game_user.game')->find(Auth::id());

        $games = array();

        foreach ($user->game_user as $game_user) {
           $games[] = $game_user->game;
        }

        $user->games = $games;

        return Inertia::render('Publishers/Show', [
            'user' => $user,
            'games' => $games
        ]);
    }
}
