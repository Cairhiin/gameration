<?php

namespace App\Actions\Publishers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(Request $request, Publisher $publisher): ?Publisher
    {
        try {
            DB::beginTransaction();

            $publisher->update(
                [
                    'name' => $request->name,
                    'city' => $request->city,
                    'country' => $request->country,
                    'year' => $request->year
                ]
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        } finally {
            return $publisher;
        }
    }

    public function asController(Request $request, Publisher $publisher): RedirectResponse
    {
        $publisher = $this->handle($request, $publisher);

        $message = $publisher ? "Publisher updated successfully!" : "There was a problem updating the publisher!";

        return Redirect::route("publishers.show", $publisher)->with("message", $message);
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
        return 'updatePublisher';
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
