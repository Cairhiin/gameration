<?php

namespace App\Actions\Genres;

use App\Models\Genre;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(ActionRequest $request, Genre $genre): ?Genre
    {
        try {
            DB::beginTransaction();

            $genre->update(
                [
                    'name' => $request->name,
                ]
            );

            DB::commit();

            return $genre;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(ActionRequest $request, Genre $genre): RedirectResponse
    {
        $message = $this->handle($request, $genre) ? "Genre" . SystemMessage::UPDATE_SUCCESS : "Genre" . SystemMessage::UPDATE_FAILURE;

        return Redirect::route("genres.show", $genre->id)->with("message", $message);
    }

    public function authorize(ActionRequest $request): bool
    {
        return Gate::allows('genre:update');
    }

    /**
     * Returns an array of validation rules for the `name`.
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
        return 'updateGenre';
    }

    /**
     * Returns an array of validation messages for the 'name'.
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
