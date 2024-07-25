<?php

namespace App\Actions\Publishers;

use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class Create
{
    use AsAction;

    public function handle()
    {
        return Inertia::render('Publishers/Create');
    }
}
