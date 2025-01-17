<?php

namespace App\Actions\Games;

use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Edit
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController(Game $game): Response
    {
        return Inertia::render('Games/Edit', [
            'game' => $game->load('genres', 'developer', 'publisher')
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('game:update');
    }
}
