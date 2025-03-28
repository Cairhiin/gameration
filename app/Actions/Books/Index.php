<?php

namespace App\Actions\Books;

use App\Models\Book;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle()
    {
        return Book::orderBy('published_at', 'desc')->take(20)->get();
    }

    public function asController(): Response
    {
        return Inertia::render('Books/Index', [
            'books' => $this->handle()->load(['genres', 'authors', 'narrators', 'publisher', 'series'])
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('book:viewAny');
    }
}
