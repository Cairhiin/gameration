<?php

namespace App\Actions\Books\Image;

use App\Models\Book;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(ActionRequest $request, Book $book): bool
    {
        try {
            $path = $request->file('image') ? Storage::disk('public')->put('images', $request->file('image')) : null;

            if ($book->image && $path) {
                Storage::disk('public')->delete($book->image);
            }

            DB::beginTransaction();

            return $book->update(
                [
                    'image' => $path
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function asController(ActionRequest $request, Book $book): RedirectResponse
    {
        $success = $this->handle($request, $book);

        if (!$success) {
            return redirect()->route('books.show', $book->id)->with('message', 'Book' . SystemMessage::UPDATE_FAILURE);
        }

        return redirect()->route('books.show', $book->id)->with('message', 'Book' . SystemMessage::UPDATE_SUCCESS);
    }

    public function authorize(): bool
    {
        return Gate::allows('book:update');
    }
}
