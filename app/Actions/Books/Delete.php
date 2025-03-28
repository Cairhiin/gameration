<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(Book $book)
    {
        try {
            if (File::exists(storage_path('app/public/' . $book->image))) {
                File::delete(storage_path('app/public/' . $book->image));
            }

            return $book->delete();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function asController(Book $book): RedirectResponse
    {
        if ($this->handle($book)) {
            return redirect()->route("books.index")->with('message', 'Book' . SystemMessage::DELETE_SUCCESS);
        } else {
            return redirect()->route("books.show", $book->id)->with('message', 'Book' . SystemMessage::DELETE_FAILURE);
        }
    }

    public function authorize(): bool
    {
        return Gate::allows("book:delete");
    }
}
