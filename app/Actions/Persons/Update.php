<?php

namespace App\Actions\Persons;

use App\Models\Person;
use App\Enums\SystemMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(Request $request, Person $person): bool
    {
        try {
            DB::beginTransaction();

            $person->update(
                [
                    'name' => $request->name,
                    'type' => $request->type,
                    'description' => $request->description,
                ]
            );

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function asController(Request $request, Person $person): RedirectResponse
    {
        $message = $this->handle($request, $person) ? "Person" . SystemMessage::UPDATE_SUCCESS : "Person" . SystemMessage::UPDATE_FAILURE;

        return Redirect::route("persons.show", $person->id)->with("message", $message);
    }

    public function authorize(): bool
    {
        return Gate::allows('person:update');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:author,narrator,both',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The selected type is invalid.',
            'type.string' => 'The type must be a string.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 1000 characters.',
        ];
    }
}
