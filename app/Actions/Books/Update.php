<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Enums\BookType;
use App\Models\Publisher;
use App\Enums\SystemMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(Request $request, Book $book): ?Book
    {
        $publisher = Publisher::find($request->input('publisher'));

        try {
            DB::beginTransaction();

            $book->update(
                [
                    'title' => $request->input('title'),
                    'user_id' => Auth::id(),
                    'description' => $request->input('description'),
                    'ISBN' => $request->input('ISBN'),
                    'publisher_id' => $publisher->id,
                    'published_at' => $request->input('published_at'),
                    'type' => $request->input('type'),
                    'pages' => $request->input('type') !== BookType::AUDIOBOOK ? $request->input('pages') : null,
                    'time' => $request->input('type') === BookType::AUDIOBOOK ? $request->input('time') : null,
                    'series_book_number' => $request->input('series_book_number'),
                    'series_id' => $request->input('series')
                ]
            );

            $book->genres()->sync($request->genres);
            $book->authors()->sync($request->authors);

            if ($request->input('type') === BookType::AUDIOBOOK) {
                $book->narrators()->sync(collect($request->input('narrators')));
            };

            DB::commit();

            return $book;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return null;
        }
    }

    public function asController(Request $request, Book $book): RedirectResponse
    {
        $success = $this->handle($request, $book);

        if (!$success) {
            return redirect()->route('books.show', $book->id)->with('message', 'Book' . SystemMessage::UPDATE_FAILURE);
        }

        return redirect()->route('books.show', $book->id)->with('message', 'Book' . SystemMessage::UPDATE_SUCCESS);
    }

    public function authorize(): bool
    {
        return Gate::allows("book:update");
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
            'publisher' => ['required', 'exists:publishers,id'],
            'published_at' => ['required', 'date', 'before:tomorrow'],
            'ISBN' => ['required', 'string', 'min:10', 'max:16', 'unique:books,ISBN,' . request()->route('book')->id . ',id'],
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
        return 'updateBook';
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
