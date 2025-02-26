<?php

namespace App\Actions\Publishers;

use App\Models\Publisher;
use App\Enums\SystemMessage;
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

    public function handle(ActionRequest $request): ?Publisher
    {
        try {
            DB::beginTransaction();

            $publisher = Publisher::create(
                [
                    'name' => $request->name,
                    'user_id' => Auth::id(),
                    'city' => $request->city,
                    'country' => $request->country,
                    'year' => $request->year
                ]
            );

            DB::commit();

            return $publisher;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        $publisher = $this->handle($request);

        if ($publisher) {
            return Redirect::route("publishers.show", $publisher->id)->with("message", "Publisher" . SystemMessage::STORE_SUCCESS);
        } else {
            return Redirect::route("publishers.create")->with("message", "Publisher" . SystemMessage::STORE_FAILURE);
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('publisher:create');
    }

    /**
     * Returns an array of validation rules for the `name` field.
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
        return 'createPublisher';
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
            'name.string' => 'The publisher name must be a string.',
            'city.required' => 'Looks like you forgot to give the publisher a city.',
            'city.string' => 'The publisher city must be a string.',
            'country.required' => 'Looks like you forgot to give the publisher a country.',
            'country.string' => 'The publisher country must be a string.',
            'year.required' => 'Looks like you forgot to give the publisher a year of founding.',
            'year.numeric' => 'The publisher year of founding must be a number.',
            'year.min' => 'The publisher year of founding must be at least 1800.',
            'year.max' => 'The publisher year of founding must be at most ' . date('Y'),
        ];
    }
}
