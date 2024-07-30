<?php

namespace App\Actions\Developers;

use App\Models\Developer;
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

            $developer = Developer::create(
                [
                    'name' => $request->name,
                    'user_id' => Auth::id(),
                    'city' => $request->city,
                    'country' => $request->country,
                    'year' => $request->year
                ]
            );

            return $developer->id;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        } finally {
            DB::commit();
        }
    }

    public function asController(Request $request): RedirectResponse
    {
        $developer_id = $this->handle($request);

        if ($developer_id) {
            return Redirect::route("developers.index")->with("message", "The developer has been added successfully!");
        } else {
            return Redirect::route("developers.create")->with("message", "The developer already exists!");
        }
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
            'name.required' => 'Looks like you forgot to give the developer a name.',
            'name.min' => 'Looks like the developer has a too short name.',
        ];
    }
}
