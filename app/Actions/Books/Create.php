<?php

namespace App\Actions\Books;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;
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
        return Inertia::render("Books/Create");
    }

    public function authorize(): bool
    {
        return Gate::allows("book:create");
    }
}
