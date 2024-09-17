<?php

namespace App\Actions\Games;

use App\Models\GameUser;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowAllRatings
{
    use AsAction;

    public function handle(string $id): Collection
    {
        return GameUser::where('game_id', $id)->orderBy('updated_at', 'desc')->get(['rating', 'id']);
    }
}
