<?php

namespace App\Actions\Genres;

use Inertia\Inertia;
use App\Models\Genre;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class Index
{
    use AsAction;

    public function handle(): ?Collection
    {
        return Genre::all();
    }

    public function asController(): Response
    {
        $genres = $this->handle();

        return Inertia::render('Genres/Index', [
            'genres' => $genres
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('genre:viewAny');
    }
}
