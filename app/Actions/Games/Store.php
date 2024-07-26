<?php

namespace App\Actions\Games;

use App\Models\Developer;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(ActionRequest $request): string
    {
        $developer = Developer::findOrFail($request->input('developer')["id"]);
        $genre = Genre::findOrFail($request->input('genre')["id"]);
        $publisher = Publisher::findOrFail($request->input('publisher')["id"]);

        $game = Game::create(
            [
                'name' => $request->name,
                'description' => $request->description,
                'developer_id' => $developer->id,
                'genre_id' => $genre->id,
                'publisher_id' => $publisher->id,
                'image' => $request->image,
                'released_at' => $request->released
            ]
        );

        return $game->id;
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        $game_id = $this->handle($request);

        return to_route("games.show", $game_id)->with("message", "The game has been added successfully!");
    }

    /**
     * Returns an array of validation rules for the `name`, `description`, `genre`, `developer`, `publisher`, `released`, and `image` fields.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3'],
            'description' => ['required'],
            'genre' => ['required'],
            'developer' => ['required'],
            'publisher' => ['required'],
            'released' => ['required'],
            'image' => ['mimes:jpg,bmp,png']
        ];
    }

    /**
     * Returns the validation error bag for the 'createGame' validation.
     *
     * @return string The validation error bag name.
     */
    public function getValidationErrorBag(): string
    {
        return 'createGame';
    }

    /**
     * Returns an array of validation messages for the 'name', 'description', 'publisher', 'developer', and 'genre' fields.
     *
     * @return array An associative array where the keys are the validation rules and the values are the corresponding error messages.
     */
    public function getValidationMessages(): array
    {
        return [
            'name.required' => 'Looks like you forgot to give the game a name.',
            'name.min' => 'Looks like your game has a too short name.',
            'description.required' => 'Looks like you forgot to give the game a description.',
            'genre.required' => 'Looks like you forgot to select a genre.',
            'developer.required' => 'Looks like you forgot to give the game a developer.',
            'publisher.required' => 'Looks like you forgot to give the game a publisher.',
            'released.required' => 'Looks like you forgot to give the game a release date.',
            'image.mimes' => 'Unsupported image format. Supported formats are: jpg, bmp, png.',
        ];
    }
}
