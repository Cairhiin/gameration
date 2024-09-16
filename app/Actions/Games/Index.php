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
            $games = FilterBy::run($request) ?? $games;
        }

        try {
            if ($request->has('sortBy')) {
                $games = $games->orderBy($request->sortBy ?? 'avg_rating', $request->sortOrder ?? 'desc')->paginate();
            } else {
                $games = $games->orderBy('released_at', 'desc')->paginate();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        foreach ($games as $game) {
            $game->avg_rating = $game->calculateGameRating();
            $game->rating_count = $game->calculateNumberOfRatings();
        }

        return Inertia::render('Games/Index', [
            'games' => $games
        ]);
    }
}
