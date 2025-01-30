<?php

namespace App\Actions\Developers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Developer;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Show
{
    use AsAction;

    public function handle(Developer $developer): Developer
    {
        return $developer;
    }

    public function asController(Developer $developer): Response
    {
        $games = $developer->games()->paginate();

        return Inertia::render('Developers/Show', [
            'developer' => $developer,
            'games' => $games
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('developer:view');
    }
}
