<?php

namespace App\Actions\Persons;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Edit
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController(): Response
    {
        return Inertia::render('Persons/Edit');
    }

    public function authorize(): bool
    {
        return Gate::allows('person:update');
    }
}
