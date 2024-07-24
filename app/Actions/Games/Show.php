<?php

namespace App\Actions\Games;

use App\Models\Game;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class Show
{
    use AsAction;

    public function handle(String $id)
    {
        $game = Game::findOrFail($id);

        return Inertia::render('Games/Show', [
            'game' => $game
        ]);
    }
}
