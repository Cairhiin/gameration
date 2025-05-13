<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Enums\BookType;
use App\Models\Publisher;
use App\Enums\SystemMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(Request $request): ?Book
    {
        $publisher = Publisher::findOrFail($request->input('publisher'));

        try {
            $path = $request->file('image') ? Storage::disk('public')->put('images', $request->file('image')) : null;

            DB::beginTransaction();

            $book = Book::create(
                [
                    'title' => $request->input('title'),
                    'user_id' => Auth::id(),
                    'description' => $request->input('description'),
                    'ISBN' => $request->input('ISBN'),
                    'publisher_id' => $publisher->id,
                    'image' => $path,
                    'published_at' => $request->input('published_at'),
                    'type' => $request->input('type'),
                    'pages' => $request->input('type') !== BookType::AUDIOBOOK ? $request->input('pages') : null,
                    'time' => $request->input('type') === BookType::AUDIOBOOK ? $request->input('time') : null,
                    'series_book_number' => $request->input('series_book_number'),
                    'series_id' => $request->input('series')
                ]
            );

            $book->genres()->sync(collect($request->input('genres')));
            $book->authors()->sync(collect($request->input('authors')));

            if ($request->input('type') === BookType::AUDIOBOOK) {
                $book->narrators()->sync(collect($request->input('narrators')));
            }

            DB::commit();

            return $book;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return null;
        }
    }

    public function asController(Request $request): RedirectResponse
    {
        $book = $this->handle($request);

        if ($book) {
            return Redirect::route("books.show", $book->id)->with("message", "Book" . SystemMessage::STORE_SUCCESS);
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
            'pages' => ['required_if:type,physical,type,ebook', 'integer', 'min:0'],
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['required', 'exists:genres,id'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['required', 'exists:persons,id'],
            'narrators' => ['required_if:type,audiobook', 'array'],
            'narrators.*' => ['exists:persons,id'],
            'series' => ['exists:series,id'],
            'series_book_number' => ['required_if:series,!=,null', 'integer', 'min:1'],
            'publisher' => ['required', 'exists:publishers,id'],
            'published_at' => ['required', 'date', 'before:tomorrow'],
            'time' => ['required_if:type,audiobook', 'string', 'min:5', 'max:5'],
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
            'title.min' => 'Looks like your book has a too short name.',
            'description.required' => 'Looks like you forgot to give the book a description.',
            'description.min' => 'The description must be at least 20 characters.',
            'genres.required' => 'Looks like you forgot to select a genre.',
            'genres.array' => 'Genres must be an array.',
            'genres.min' => 'You must select at least one genre.',
            'genres.*.exists' => 'One or more selected genres are invalid.',
            'authors.required' => 'Looks like you forgot to give the book an author.',
            'authors.array' => 'Authors must be an array.',
            'authors.min' => 'You must select at least one author.',
            'authors.*.exists' => 'One or more selected authors are invalid.',
            'narrators.required_if' => 'Narrators are required for audiobooks.',
            'narrators.array' => 'Narrators must be an array.',
            'narrators.*.exists' => 'One or more selected narrators are invalid.',
            'series_id.exists' => 'The selected series is invalid.',
            'series_book_number.required_if' => 'The book number is required when a series is selected.',
            'series_book_number.integer' => 'The book number must be an integer.',
            'series_book_number.min' => 'The book number must be at least 1.',
            'publisher.required' => 'Looks like you forgot to select a publisher.',
            'publisher.exists' => 'The selected publisher is invalid.',
            'published_at.required' => 'Looks like you forgot to give the book a publishing date.',
            'published_at.before' => 'The publishing date must be in the past.',
            'published_at.date' => 'The publishing date must be a valid date.',
            'pages.required_if' => 'The number of pages is required for physical and ebook types.',
            'pages.integer' => 'The number of pages must be an integer.',
            'pages.min' => 'The number of pages must be at least 1.',
            'time.required_if' => 'The duration is required for audiobooks.',
            'time.string' => 'The duration must be a string.',
            'time.min' => 'The duration must be at least 5 characters.',
            'time.max' => 'The duration must be at most 5 characters.',
            'ISBN.required' => 'Looks like you forgot to give the book an ISBN.',
            'ISBN.min' => 'The ISBN must be at least 10 characters.',
            'ISBN.max' => 'The ISBN must be at most 16 characters.',
            'ISBN.unique' => 'The ISBN must be unique.',
            'image.max' => 'The image size must be less than 2MB.',
            'image.mimes' => 'Unsupported image format. Supported formats are: jpg, bmp, png.',
        ];
    }
}
