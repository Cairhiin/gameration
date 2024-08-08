<?php

namespace App\Actions\Games;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterBy
{
    use AsAction;

    public function handle(Request $request): ?Builder
    {
        $games = null;

        $filterBy = $request->filterBy;
        $filter = key($filterBy);
        $filterBy = reset($filterBy);

        try {
            $games = Game::whereHas($filter, function ($query) use ($filterBy) {
                return $query->where('name', '=', $filterBy);
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return $games;
    }
}
