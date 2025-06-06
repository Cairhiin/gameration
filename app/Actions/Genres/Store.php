<?php

namespace App\Actions\Genres;

use App\Models\Genre;
use App\Enums\SystemMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(ActionRequest $request): ?Genre
    {
        try {
            DB::beginTransaction();

            $genre = Genre::create(
                ['name' => $request->name]
            );

            DB::commit();

            return $genre;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        $genre = $this->handle($request);

        if ($genre) {
            return Redirect::route("genres.show", $genre->id)->with("message", "Genre" . SystemMessage::STORE_SUCCESS);
        } else {
            return Redirect::route("genres.create")->with("message", "Genre" . SystemMessage::STORE_FAILURE);
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('genre:create');
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
