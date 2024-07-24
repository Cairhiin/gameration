<?php

namespace App\Actions\Genres;

use Inertia\Inertia;
use App\Models\Genre;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle()
    {
        $genres = Genre::all();

        return Inertia::render('Genres/Index', [
            'genres' => $genres
        ]);
    }
}
