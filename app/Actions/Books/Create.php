<?php

namespace App\Actions\Books;

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
        return Inertia::render("Books/Create");
    }

    public function authorize()
    {
        return Gate::allows("book:create");
    }
}
