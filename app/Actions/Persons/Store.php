<?php

namespace App\Actions\Persons;

use App\Models\Person;
use App\Enums\SystemMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(Request $request): ?Person
    {
        try {
            $path = $request->file('image') ? Storage::disk('public')->put('images', $request->file('image')) : null;

            DB::beginTransaction();

            $newGame = Person::create(
                [
                    'name' => $request->name,
                    'type' => $request->type,
                    'description' => $request->description,
                    'image' => $path,
                ]
            );

            DB::commit();

            return $newGame;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(Request $request): RedirectResponse
    {
        $person = $this->handle($request);

        if ($person) {
            return redirect()->route('persons.show', $person->id)->with('message', 'Person' . SystemMessage::STORE_SUCCESS)->with('success')->with("message", "Person" . SystemMessage::STORE_SUCCESS);
        }

        return redirect()->route('persons.index')->with("message", "Person" . SystemMessage::STORE_FAILURE);
    }

    public function authorize(): bool
    {
        return Gate::allows('person:create');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:author,narrator,both',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'image.image' => 'The image must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
        ];
    }
}
