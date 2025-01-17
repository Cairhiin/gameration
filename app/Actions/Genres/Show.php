<?php

namespace App\Actions\Genres;

use Inertia\Inertia;
use App\Models\Genre;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Show
{
    use AsAction;

    public function handle(Genre $genre, Request $request): BelongsToMany
    {
        if ($request->has('sortBy') && $request->sortBy === 'avg_rating') {
            return $genre->gamesByRating();
        } else {
            return $genre->gamesByDate();
        }
    }

    public function asController(Genre $genre, Request $request): Response
    {
        $games = $this->handle($genre, $request)->paginate();

        return Inertia::render('Genres/Show', [
            'genre' => $genre,
            'games' => $games
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('genre:view');
    }
}
