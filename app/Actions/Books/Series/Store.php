<?php

namespace App\Actions\Books\Series;

use App\Models\Series;
use App\Enums\BookType;
use App\Enums\SystemMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(Request $request): ?Series
    {
        try {
            DB::beginTransaction();

            $series = Series::create(
                [
                    'title' => $request->input('title'),
                    'user_id' => Auth::id(),
                    'description' => $request->input('description'),
                ]
            );

            $series->authors()->sync(collect($request->input('authors')));

            DB::commit();

            return $series;
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error message
            Log::error($e->getMessage());
            return null;
        }
    }

    public function asController(Request $request): RedirectResponse
    {
        $series = $this->handle($request);

        if ($series) {
            return Redirect::route("books.series.show", $series->id)->with("message", "Series" . SystemMessage::STORE_SUCCESS);
        } else {
            return Redirect::route("books.series.create")->with("message", "Series" . SystemMessage::STORE_FAILURE);
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('book:create');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:series,title|min:3',
            'description' => 'nullable|string|min:20',
            'authors' => 'array',
            'authors.*' => 'string|exists:persons,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'The title has already been taken.',
            'title.min' => 'The title must be at least 3 characters.',
            'description.string' => 'The description must be a string.',
            'description.min' => 'The description must be at least 20 characters.',
            'authors.array' => 'The authors must be an array.',
            'authors.*.string' => 'Each author ID must be a string.',
            'authors.*.exists' => 'The selected author ID is invalid.',
        ];
    }
}
