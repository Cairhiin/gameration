<?php

namespace App\Actions\Genres;

use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class Create
{
    use AsAction;

    public function handle()
    {
        return Inertia::render('Genres/Create');
    }
}
