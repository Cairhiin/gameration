<?php

namespace App\Actions\Publishers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Publisher;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Edit
{
    use AsAction;

    public function handle(Publisher $publisher)
    {
        //
    }

    public function asController(Publisher $publisher): Response
    {
        return Inertia::render('Publishers/Edit', [
            'publisher' => $publisher
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('publisher:update');
    }
}
