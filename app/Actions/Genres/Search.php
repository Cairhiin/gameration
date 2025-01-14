<?php

namespace App\Actions\Genres;

use App\Models\Genre;
use App\Traits\HasSearch;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class Search
{
    use AsAction;
    use HasSearch;

    public function handle(Request $request): ?Collection
    {
        return $this->search(Genre::class, $request->input('search'));
    }

    public function asController(Request $request): ?Collection
    {
        return $this->handle($request);
    }
}
