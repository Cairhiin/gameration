<?php

namespace App\Actions\Publishers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(Request $request): string
    {
        $publisher = Publisher::create(
            ['name' => $request->name]
        );

        return $publisher->id;
    }

    public function asController(Request $request): RedirectResponse
    {
        $publisher_id = $this->handle($request);

        return to_route("publishers.index")->with("message", "The publisher has been added successfully!");
    }

    /**
     * Returns an array of validation rules for the `name` field.
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
        ];
    }
}
