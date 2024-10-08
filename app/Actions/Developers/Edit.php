<?php

namespace App\Actions\Developers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Developer;
use Lorisleiva\Actions\Concerns\AsAction;

class Edit
{
    use AsAction;

    public function handle(Developer $developer): Developer
    {
        return $developer;
    }

    public function asController(Developer $developer): Response
    {
        return Inertia::render('Developers/Edit', [
            'developer' => $developer
        ]);
    }
}
