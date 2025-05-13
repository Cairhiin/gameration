<?php

namespace App\Actions\Games;

use App\Models\Game;
use App\Models\Developer;
use App\Models\Publisher;
use App\Enums\SystemMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(Request $request): ?Game
    {
        $developer = Developer::findOrFail($request->input('developer')["id"]);
        $publisher = Publisher::findOrFail($request->input('publisher')["id"]);

        $game = Game::where('name', $request->name)->where('developer_id', $developer->id)->where('publisher_id', $publisher->id)->get();

        if ($game->isNotEmpty()) {
            return null;
        }

        try {
            $path = $request->file('image') ? Storage::disk('public')->put('images', $request->file('image')) : null;

            DB::beginTransaction();

            $newGame = Game::create(
                [
                    'name' => $request->name,
                    'user_id' => Auth::id(),
                    'description' => $request->description,
                    'developer_id' => $developer->id,
                    'publisher_id' => $publisher->id,
                    'image' => $path,
                    'released_at' => $request->released
                ]
            );

            foreach ($request->input('genres') as $genre) {
                $newGame->genres()->attach($genre["id"]);
            }

            DB::commit();

            return $newGame;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(Request $request): RedirectResponse
    {
        $game = $this->handle($request);

        if ($game) {
            return Redirect::route("games.show", $game->id)->with("message", "Game" . SystemMessage::STORE_SUCCESS);
        } else {
            return Redirect::route("games.create")->with("message", "Game" . SystemMessage::STORE_FAILURE);
        }
    }

    public function authorize(Game $game): bool
    {
        return Gate::allows('game:create', $game);
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
            'description' => ['required', 'min:20'],
            'genres' => ['required'],
            'developer' => ['required'],
            'publisher' => ['required'],
            'released' => ['required', 'date', 'before:tomorrow'],
            'image' => ['nullable', 'mimes:jpg,bmp,png', 'max:2048']
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
            'genres.required' => 'Looks like you forgot to select a genre.',
            'developer.required' => 'Looks like you forgot to give the game a developer.',
            'publisher.required' => 'Looks like you forgot to give the game a publisher.',
            'released.required' => 'Looks like you forgot to give the game a release date.',
            'released.before' => 'The release date must be in the past.',
            'released.date' => 'The release date must be a valid date.',
            'image.max' => 'The image size must be less than 2MB.',
            'image.mimes' => 'Unsupported image format. Supported formats are: jpg, bmp, png.',
        ];
    }
}
