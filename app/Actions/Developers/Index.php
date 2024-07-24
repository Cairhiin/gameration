<?php

namespace App\Actions\Developers;

use Inertia\Inertia;
use App\Models\Developer;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle()
    {
        $developers = Developer::all();

        return Inertia::render('Developers/Index', [
            'developers' => $developers
        ]);
    }
}
