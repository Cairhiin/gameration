<?php

namespace App\Actions\Games;

use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class Edit
{
    use AsAction;

    public function handle(Game $game): Game
    {
        return $game;
    }

    public function asController(Game $game): Response
    {
        return Inertia::render('Games/Edit', [
            'game' => $game->load('genres', 'developer', 'publisher')
        ]);
    }
}
