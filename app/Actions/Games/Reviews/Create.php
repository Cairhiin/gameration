<?php

namespace App\Actions\Games\Reviews;

use App\Models\Game;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class Create
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController(Game $game): Response
    {
        return Inertia::render('Games/Reviews/Create', [
            'game' => $game
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('review:create');
    }
}
