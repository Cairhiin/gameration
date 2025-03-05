<?php

namespace App\Actions\Games;

use Carbon\Carbon;
use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
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
                    $games = Cache::remember($request->sortBy === 'released_at' ? 'gamesByDatePaginated' : "gamesByNamePaginated", now()->addMinutes(10), function () use ($games) {
                        return $games->orderBy($request->sortBy ?? 'name', $request->sortOrder ?? 'desc')->paginate(15);
                    });
                }
            }
        } else {
            $games = Cache::remember("gamesByDatePaginated", now()->addMinutes(10), function () use ($games) {
                return $games->orderBy('released_at', 'desc')->paginate(15);
            });
        }

        return Inertia::render('Games/Index', [
            'games' => $games
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('game:viewAny');
    }

    public function getByRating(Request $request): Collection
    {
        $games = $this->getCachedGames();

        if ($request->sortOrder === 'asc') {
            return $games->sortBy(function ($game) {
                return $game->getAvgRatingAttribute();
            });
        }

        return $games->sortByDesc(function ($game) {
            return $game->getAvgRatingAttribute();
        });
    }

    public function getByPopularity(Request $request): Collection
    {
        $games = Cache::remember("gamesByPopularity", now()->addMinutes(10), function () {
            return Game::whereYear('released_at', Carbon::now()->year)->get();
        });

        if ($request->sortOrder === 'asc') {
            return $games->sortBy(function ($game) {
                return $game->getAvgRatingAttribute();
            });
        }

        return $games->sortByDesc(function ($game) {
            return $game->getAvgRatingAttribute();
        });
    }

    public function getCachedGames(): Collection
    {
        return Cache::remember("games", now()->addMinutes(10), function () {
            return Game::all();
        });
    }
}
