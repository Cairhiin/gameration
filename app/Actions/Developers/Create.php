<?php

namespace App\Actions\Developers;

use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class Create
{
    use AsAction;

    public function handle()
    {
        return Inertia::render('Developers/Create');
    }
}
