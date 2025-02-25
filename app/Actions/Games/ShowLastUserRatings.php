<?php

namespace App\Actions\Games;

use App\Models\Game;
use App\Models\GameUser;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowLastUserRatings
{
    use AsAction;

    public function handle(string $id): Collection
    {
        return GameUser::where('game_id', $id)->where('rating', '!=', '0')->with(['user' => function ($query) {
            $query->select('id', 'username');
        }])->orderBy('updated_at', 'desc')->take(10)->get();
    }
}
