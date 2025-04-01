<?php

namespace App\Actions\Books\Image;

use App\Models\Book;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class Destroy
{
    use AsAction;

    public function handle(Book $book): bool
    {
        try {
            if (Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }

            return $book->update([
                'image' => null,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public function asController(Book $book): RedirectResponse
    {
        $success = $this->handle($book);

        if (!$success) {
            return redirect()->route('books.show', $book->id)->with('message', 'Book' . SystemMessage::DELETE_FAILURE);
        }

        return redirect()->route('books.show', $book->id)->with('message', 'Book' . SystemMessage::DELETE_SUCCESS);
    }

    public function authorize(): bool
    {
        return Gate::allows('book:delete');
    }
}
