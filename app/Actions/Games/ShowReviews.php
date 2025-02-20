<?php

namespace App\Actions\Games;

use App\Models\Game;
use App\Models\GameUser;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ShowReviews
{
    use AsAction;

    public function handle(Game $game): LengthAwarePaginator
    {
        $review = GameUser::where('game_id', $game->id)
            ->whereNot('user_id', Auth::id())
            ->whereNotNull('content')
            ->join('users', 'game_user.user_id', '=', 'users.id')
            ->select('game_user.*', 'users.username');

        /** @var User $user */
        $user = Auth::user();

        if ($user->isAdmin() || $user->isModerator()) {
            return $review->orderBy('game_user.created_at', 'desc')->paginate(10);
        }

        return $review->where('approved', '1')
            ->orderBy('game_user.created_at', 'desc')
            ->paginate(10);
    }

    public function asController(Game $game): LengthAwarePaginator
    {
        return $this->handle($game);
    }

    public function authorize(): bool
    {
        return Gate::allows('review:viewAny');
    }
}
