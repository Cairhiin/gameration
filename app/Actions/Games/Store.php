<?php

namespace App\Actions\Games;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(Request $request): string
    {
        $game = Game::create($request->all());

        return $game->id;
    }

    public function asController(Request $request): RedirectResponse
    {
        $game_id = $this->handle($request);

        return to_route("games.show", $game_id)->with("message", "The game has been added successfully!");
    }

    /**
     * Returns an array of validation rules for the `name`, `description`, and `type` fields.
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
            'name.required' => 'Looks like you forgot to give the Game a name.',
            'name.min' => 'Looks like your Game has a too short name.',
            'description.required' => 'Looks like you forgot to give the Game a description.',
            'genre.required' => 'Looks like you forgot to select a Game Type.',
            'developer.required' => 'Looks like you forgot to give the Game a Developer.',
            'publisher.required' => 'Looks like you forgot to give the Game a Publisher.',
        ];
    }
}
