<?php

namespace App\Actions\Books;

use App\Models\BookUser;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowAllRatings
{
    use AsAction;

    public function handle(string $id): Collection
    {
        return BookUser::where('book_id', $id)->where('rating', '!=', '0')->orderBy('updated_at', 'desc')->get(['rating']);
    }
}
