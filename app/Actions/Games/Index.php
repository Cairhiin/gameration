<?php

namespace App\Actions\Games;

use App\Models\Game;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle(Request $request)
    {
        $games = Game::with('developer', 'genres');

        if ($request->has('filterBy')) {
            $games = FilterBy::run($request) ?? $games;
        }

        if ($request->has('sortBy')) {
            $games = $games->orderBy($request->sortyBy ?? 'avg_rating', $request->sortOrder ?? 'desc')->paginate();
        } else {
            $games = $games->orderBy('released_at', 'desc')->paginate();
        }

        return Inertia::render('Games/Index', [
            'games' => $games
        ]);
    }
}
