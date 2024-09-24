<?php

namespace App\Actions\Games;

use App\Models\Game;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Pagination\LengthAwarePaginator;

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

                /* Adding custom paginator for average rating sorting */
                if ($request->sortBy === 'avg_rating') {
                    if ($request->has('sortOrder') && $request->sortOrder === 'asc') {
                        $data = Game::all()->sortBy(function ($game) {
                            return $game->getAvgRatingAttribute();
                        });
                    } else {
                        $data = Game::all()->sortByDesc(function ($game) {
                            return $game->getAvgRatingAttribute();
                        });
                    }

                    $currentPage = $request->page ?? 1;
                    $itemsPerPage = 15;
                    $items = array_slice($data->toArray(), ($currentPage - 1) * $itemsPerPage, $itemsPerPage);
                    $games = new LengthAwarePaginator($items, count($data->toArray()), $itemsPerPage, $currentPage, ['path' => url('games?sortBy=avg_rating&sortOrder=' . ($request->sortOrder ?? 'desc'))]);
                } else {

                    $games = $games->orderBy($request->sortBy ?? 'name', $request->sortOrder ?? 'desc')->paginate();
                }
            } else {
                $games = $games->orderBy('released_at', 'desc')->paginate();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return Inertia::render('Games/Index', [
            'games' => $games
        ]);
    }
}
