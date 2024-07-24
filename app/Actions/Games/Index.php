<?php

namespace App\Actions\Games;

use App\Models\Game;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle()
    {
        $games = Game::all()->sortByDesc('created_at');

        if (!$games->isEmpty()) {
            $games = $games->toQuery()->paginate();
        }

        return Inertia::render('Games/Index', [
            'games' => $games
        ]);
    }
}
