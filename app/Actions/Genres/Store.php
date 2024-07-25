<?php

namespace App\Actions\Genres;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(Request $request): string
    {
        $genre = Genre::create(
            ['name' => $request->name]
        );

        return $genre->id;
    }

    public function asController(Request $request): RedirectResponse
    {
        $genre_id = $this->handle($request);

        return to_route("genres.index")->with("message", "The genre has been added successfully!");
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
        ];
    }

    /**
     * Returns the validation error bag for the 'createGenre' validation.
     *
     * @return string The validation error bag name.
     */
    public function getValidationErrorBag(): string
    {
        return 'createGenre';
    }

    /**
     * Returns an array of validation messages for the 'name' field.
     *
     * @return array An associative array where the keys are the validation rules and the values are the corresponding error messages.
     */
    public function getValidationMessages(): array
    {
        return [
            'name.required' => 'Looks like you forgot to give the genre a name.',
            'name.min' => 'Looks like your genre has a too short name.',
        ];
    }
}
