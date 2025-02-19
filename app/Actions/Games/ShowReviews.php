<?php

namespace App\Actions\Games;

use App\Models\Game;
use App\Models\GameUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Pagination\LengthAwarePaginator;

class ShowReviews
{
    use AsAction;

    public function handle(Game $game): LengthAwarePaginator
    {
        return GameUser::where('game_id', $game->id)->where('approved', '1')->whereNot('user_id', Auth::id())->orderBy('updated_at', 'desc')->with('user')->paginate(5);
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
