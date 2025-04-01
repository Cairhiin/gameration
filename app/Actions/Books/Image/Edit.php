<?php

namespace App\Actions\Books\Image;

use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Edit
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController(Book $book)
    {
        return Inertia::render('Books/Image/Edit', [
            'image' => $book->image
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('book:update');
    }
}
