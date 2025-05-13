<?php

namespace App\Actions\Persons;

use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class Create
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController(): Response
    {
        return Inertia::render('Persons/Create');
    }

    public function authorize(): bool
    {
        return Gate::allows('person:create');
    }
}
