<?php

namespace App\Actions\Genres;

use App\Models\Genre;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(Genre $genre): bool
    {
        try {
            $genre->delete();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function asController(Genre $genre): RedirectResponse
    {
        $genre = $this->handle($genre);

        if (!$genre) {
            return Redirect::route("genres.show", $genre->id)->with("message", "We could not delete the genre!");
        } else {
            return Redirect::route("genres.index")->with("message", "The genre has been deleted!");
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('genre:delete');
    }
}
