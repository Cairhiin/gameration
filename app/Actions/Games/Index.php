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
        $games = Game::with('developer', 'genres')->orderBy('created_at')->paginate();

        return Inertia::render('Games/Index', [
            'games' => $games
        ]);
    }
}
