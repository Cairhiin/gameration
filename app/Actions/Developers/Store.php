<?php

namespace App\Actions\Developers;

use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(ActionRequest $request): ?Developer
    {
        try {
            DB::beginTransaction();

            $developer = Developer::create(
                [
                    'name' => $request->name,
                    'user_id' => Auth::id(),
                    'city' => $request->city,
                    'country' => $request->country,
                    'year' => $request->year
                ]
            );

            DB::commit();

            return $developer;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        $developer = $this->handle($request);

        if ($developer) {
            return Redirect::route("developers.show", $developer->id)->with("message", "The developer has been added successfully!");
        } else {
            return Redirect::route("developers.create")->with("message", "The developer already exists!");
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('developer:create');
    }

    /**
     * Returns an array of validation rules for the `name`, `description`, and `type` fields.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'string'],
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
            'year' => ['required', 'numeric', 'min:1800', 'max:' . date('Y')],
        ];
    }

    /**
     * Returns the validation error bag for the 'createGenre' validation.
     *
     * @return string The validation error bag name.
     */
    public function getValidationErrorBag(): string
    {
        return 'createDeveloper';
    }

    /**
     * Returns an array of validation messages for the 'name' field.
     *
     * @return array An associative array where the keys are the validation rules and the values are the corresponding error messages.
     */
    public function getValidationMessages(): array
    {
        return [
            'name.required' => 'Looks like you forgot to give the publisher a name.',
            'name.min' => 'Looks like your publisher has a too short name.',
            'name.string' => 'The developer name must be a string.',
            'city.required' => 'Looks like you forgot to give the publisher a city.',
            'city.string' => 'The developer city must be a string.',
            'country.required' => 'Looks like you forgot to give the publisher a country.',
            'country.string' => 'The developer country must be a string.',
            'year.required' => 'Looks like you forgot to give the publisher a year of founding.',
            'year.numeric' => 'The developer year of founding must be a number.',
            'year.min' => 'The developer year of founding must be at least 1800.',
            'year.max' => 'The developer year of founding must be at most ' . date('Y'),
        ];
    }
}
