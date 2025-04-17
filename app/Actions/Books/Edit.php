<?php

namespace App\Actions\Books;

use App\Models\Book;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Edit
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController(Book $book): Response
    {
        return Inertia::render('Books/Edit', [
            'book' => $book->load('genres', 'series', 'publisher', 'authors', 'narrators'),
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('book:update');
    }
}
