<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Enums\BookType;
use App\Models\Publisher;
use App\Enums\SystemMessage;
use Illuminate\Validation\Rule;
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

    public function handle(ActionRequest $request): ?Book
    {
        $publisher = Publisher::findOrFail($request->input('publisher_id'));

        $game = Book::where('title', $request->name)->where('publisher_id', $publisher->id)->get();

        if ($game->isNotEmpty()) {
            return null;
        }

        try {
            $path = $request->file('image') ? $request->file('image')->store('images', 'public') : null;

            DB::beginTransaction();

            $book = Book::create(
                [
                    'title' => $request->title,
                    'user_id' => Auth::id(),
                    'description' => $request->description,
                    'ISBN' => $request->ISBN,
                    'publisher_id' => $publisher,
                    'image' => $path,
                    'published_at' => $request->published_at,
                    'type' => $request->type,
                    'pages' => $request->pages,
                    'series_id' => $request->series
                ]
            );

            $book->genres()->sync(collect($request->input('genres')));
            $book->authors()->sync(collect($request->input('authors')));
            $book->narrators()->sync(collect($request->input('narrators')));

            DB::commit();

            return $book;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        $game = $this->handle($request);

        if ($game) {
            return Redirect::route("books.show", $game->id)->with("message", "Book" . SystemMessage::STORE_SUCCESS);
        } else {
            return Redirect::route("books.create")->with("message", "Book" . SystemMessage::STORE_FAILURE);
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('book:create');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3'],
            'description' => ['required', 'min:20'],
            'type' => ['required', Rule::enum(BookType::class)],
            'pages' => ['required', 'integer', 'min:1'],
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['required', 'exists:genres,id'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['required', 'exists:persons,id'],
            'narrators' => ['array'],
            'narrators.*' => ['exists:persons,id'],
            'series_id' => ['exists:series,id'],
            'publisher_id' => ['required', 'exists:publishers,id'],
            'published_at' => ['required', 'date', 'before:tomorrow'],
            'ISBN' => ['required', 'string', 'min:10', 'max:16', 'unique:books'],
            'image' => ['nullable', 'mimes:jpg,bmp,png', 'max:2048']
        ];
    }

    /**
     * Returns the validation error bag for the 'createGame' validation.
     *
     * @return string The validation error bag name.
     */
    public function getValidationErrorBag(): string
    {
        return 'createBook';
    }

    public function getValidationMessages(): array
    {
        return [
            'title.required' => 'Looks like you forgot to give the book a title.',
            'title.min' => 'Looks like your game has a too short name.',
            'description.required' => 'Looks like you forgot to give the book a description.',
            'genres.required' => 'Looks like you forgot to select a genre.',
            'published_at.required' => 'Looks like you forgot to give the book a publishing date.',
            'published_at.before' => 'The publishing date must be in the past.',
            'published_at.date' => 'The publishing date must be a valid date.',
            'author.required' => 'Looks like you forgot to give the book an author.',
            'ISBN.required' => 'Looks like you forgot to give the book an ISBN.',
            'ISBN.min' => 'The ISBN must be at least 10 characters.',
            'ISBN.max' => 'The ISBN must be at most 16 characters.',
            'image.max' => 'The image size must be less than 2MB.',
            'image.mimes' => 'Unsupported image format. Supported formats are: jpg, bmp, png.',
        ];
    }
}
