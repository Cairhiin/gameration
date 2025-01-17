<?php

namespace App\Actions\Games;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Create
{
    use AsAction;

    public function handle(): Response
    {
        return Inertia::render('Games/Create');
    }

    public function authorize(): bool
    {
        return Gate::allows('game:create');
    }
}
