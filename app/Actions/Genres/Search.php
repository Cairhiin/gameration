<?php

namespace App\Actions\Genres;

use App\Models\Genre;
use App\Traits\HasSearch;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class Search
{
    use AsAction;
    use HasSearch;

    public function handle(Request $request)
    {
        return $this->search(Genre::class, $request->input('search'));
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }
}
