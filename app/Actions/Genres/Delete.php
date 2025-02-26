<?php

namespace App\Actions\Genres;

use App\Models\Genre;
use App\Enums\SystemMessage;
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
            return Redirect::route("genres.show", $genre->id)->with("message", "Genre" . SystemMessage::DELETE_FAILURE);
        } else {
            return Redirect::route("genres.index")->with("message", "Genre" . SystemMessage::DELETE_SUCCESS);
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('genre:delete');
    }
}
