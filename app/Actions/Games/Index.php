<?php

namespace App\Actions\Games;

use App\Models\Game;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle(Request $request)
    {
        $games = Game::with('developer', 'genres');

        if ($request->has('filterBy')) {
            $filterBy = $request->filterBy;
            $filter = key($filterBy);
            $filterBy = reset($filterBy);

            try {
                $games = Game::whereHas($filter, function ($query) use ($filterBy) {
                    return $query->where('name', '=', $filterBy);
                })->get();
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }

        /* if ($request->has('sortBy')) {
            $games = $games->orderBy($request->sortyBy ?? 'avg_rating', $request->sortOrder ?? 'desc');
        } else {
            $games = $games->orderBy('released_at', 'desc');
        } */

        return Inertia::render('Games/Index', [
            'games' => $games
        ]);
    }
}
