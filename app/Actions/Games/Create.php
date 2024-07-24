<?php

namespace App\Actions\Games;

use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class Create
{
    use AsAction;

    public function handle()
    {
        return Inertia::render('Games/Create');
    }
}
