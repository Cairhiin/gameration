<?php

namespace App\Actions\Genres;

use Inertia\Inertia;
use App\Models\Genre;
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

    public function asController(Genre $genre): Response
    {
        return Inertia::render('Genres/Edit', [
            'genre' => $genre
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('genre:update');
    }
}
