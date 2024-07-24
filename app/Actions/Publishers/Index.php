<?php

namespace App\Actions\Publishers;

use Inertia\Inertia;
use App\Models\Publisher;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle()
    {
        $publishers = Publisher::all();

        return Inertia::render('Publishers/Index', [
            'publishers' => $publishers
        ]);
    }
}
