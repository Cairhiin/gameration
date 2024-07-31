<?php

namespace App\Actions\Developers;

use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(ActionRequest $request, Developer $developer): ?Developer
    {
        try {
            DB::beginTransaction();

            $developer->update(
                [
                    'name' => $request->name,
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

    public function asController(ActionRequest $request, Developer $developer): RedirectResponse
    {
        $message = $this->handle($request, $developer) ? "Developer updated successfully!" : "There was a problem updating the developer!";

        return Redirect::route("developers.show", $developer->id)->with("message", $message);
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
        return 'updateDeveloper';
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
