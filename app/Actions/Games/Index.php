<?php

namespace App\Actions\Games;

use Carbon\Carbon;
use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Pagination\LengthAwarePaginator;

class Index
{
    use AsAction;

    public function handle(Request $request): Response
    {
        $allowedSorts = [
            'name',
            'released_at',
            'avg_rating',
            'popular'
        ];

        $games = Game::with('developer', 'genres');
        $data = null;

        if ($request->has('filterBy')) {
            $games = FilterBy::run($request) ?? $games;
        }

        if ($request->has('sortBy')) {

            if ($request->sortBy === 'avg_rating') {
                $data = $this->getByRating($request);
            } elseif ($request->sortBy === 'popular') {
                $data = $this->getByPopularity($request);
            }

            if ($data) {
                /* Adding custom paginator for average rating sorting */
                $currentPage = $request->page ?? 1;
                $itemsPerPage = 15;
                $items = array_slice($data->toArray(), ($currentPage - 1) * $itemsPerPage, $itemsPerPage);
                $games = new LengthAwarePaginator($items, count($data->toArray()), $itemsPerPage, $currentPage, ['path' => url('games?sortBy=avg_rating&sortOrder=' . ($request->sortOrder ?? 'desc'))]);
            } else {
                if (in_array($request->sortBy, $allowedSorts)) {
                    $games = $games->orderBy($request->sortBy ?? 'name', $request->sortOrder ?? 'desc')->paginate();
                }
            }
        } else {
            $games = $games->orderBy('released_at', 'desc')->paginate();
        }

        return Inertia::render('Games/Index', [
            'games' => $games
        ]);
    }

    public function getByRating(Request $request): Collection
    {
        if ($request->sortOrder === 'asc') {
            return Game::all()->sortBy(function ($game) {
                return $game->getAvgRatingAttribute();
            });
        } else {
            return Game::all()->sortByDesc(function ($game) {
                return $game->getAvgRatingAttribute();
            });
        }
    }

    public function getByPopularity(Request $request): Collection
    {
        if ($request->sortOrder === 'asc') {
            return Game::whereYear('released_at', Carbon::now()->year)->get()->sortBy(function ($game) {
                return $game->getAvgRatingAttribute();
            });
        } else {
            return Game::whereYear('released_at', Carbon::now()->year)->get()->sortByDesc(function ($game) {
                return $game->getAvgRatingAttribute();
            });
        }
    }
}
