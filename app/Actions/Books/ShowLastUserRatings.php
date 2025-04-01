<?php

namespace App\Actions\Books;

use App\Models\BookUser;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowLastUserRatings
{
    use AsAction;

    public function handle(string $id): Collection
    {
        return BookUser::where('book_id', $id)->where('rating', '!=', '0')->with(['user' => function ($query) {
            $query->select('id', 'username');
        }])->orderBy('updated_at', 'desc')->take(10)->get();
    }
}
