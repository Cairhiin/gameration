<?php

namespace App\Actions\Profile\Ratings;

use App\Models\Game;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle()
    {
        $user = User::find(Auth::id());

        $ratings = $user->game_user()->where('user_id', Auth::id())->orderBy('updated_at', 'desc')->with('game')->paginate(20);

        return Inertia::render('Profile/Ratings/Index', [
            'ratings' => $ratings,
        ]);
    }
}
