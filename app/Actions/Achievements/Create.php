<?php

namespace App\Actions\Achievements;

use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class Create
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController()
    {
        return Inertia::render('Achievements/Create');
    }

    public function authorize()
    {
        return Gate::allows('achievement:create');
    }
}
