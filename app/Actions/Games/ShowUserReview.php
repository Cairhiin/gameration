<?php

namespace App\Actions\Games;

use App\Models\Game;
use App\Models\GameUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowUserReview
{
    use AsAction;

    public function handle(Game $game): ?GameUser
    {
        return GameUser::where('game_id', $game->id)
            ->where('user_id', Auth::id())
            ->join('users', 'game_user.user_id', '=', 'users.id')
            ->first();
    }

    public function asController(Game $game): ?GameUser
    {
        return $this->handle($game);
    }

    public function authorize(): bool
    {
        return Gate::allows('review:view');
    }
}
