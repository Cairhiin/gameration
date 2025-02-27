<?php

namespace App\Actions\Games;

use App\Models\Game;
use App\Models\Developer;
use App\Models\Publisher;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(ActionRequest $request, Game $game): ?Game
    {
        $developer = Developer::find($request->input('developer')["id"]);
        $publisher = Publisher::find($request->input('publisher')["id"]);

        try {
            DB::beginTransaction();

            $game->update(
                [
                    'name' => $request->name,
                    'description' => $request->description,
                    'developer_id' => $developer->id,
                    'publisher_id' => $publisher->id,
                    'released_at' => $request->released,
                ]
            );

            $genre_ids = array();

            foreach ($request->genres as $genre) {
                $genre_ids[] = $genre["id"];
            }

            $game->genres()->sync($genre_ids);

            DB::commit();

            return $game;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(ActionRequest $request, Game $game): RedirectResponse
    {
        $message = $this->handle($request, $game) ? "Game" . SystemMessage::UPDATE_SUCCESS : "Game" . SystemMessage::UPDATE_FAILURE;

        return Redirect::route("games.show", $game->id)->with("message", $message);
    }

    public function authorize(): bool
    {
        return Gate::allows('game:update');
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
        return 'updateGame';
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
