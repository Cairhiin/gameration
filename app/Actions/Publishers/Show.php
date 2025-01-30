<?php

namespace App\Actions\Publishers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Publisher;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Show
{
    use AsAction;

    public function handle(Publisher $publisher)
    {
        //
    }

    public function asController(Publisher $publisher): Response
    {
        $games = $publisher->games()->paginate();

        return Inertia::render('Publishers/Show', [
            'publisher' => $publisher,
            'games' => $games
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('publisher:view');
    }
}
