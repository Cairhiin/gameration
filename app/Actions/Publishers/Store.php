<?php

namespace App\Actions\Publishers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(Request $request): ?string
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

            return $publisher->id;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        } finally {
            DB::commit();
        }
    }

    public function asController(Request $request): RedirectResponse
    {
        $publisher_id = $this->handle($request);

        if ($publisher_id) {
            return Redirect::route("publishers.show", $publisher_id)->with("message", "The publisher has been added successfully!");
        } else {
            return Redirect::route("publishers.create")->with("message", "The publisher already exists!");
        }
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
