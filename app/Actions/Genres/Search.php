<?php

namespace App\Actions\Genres;

use App\Models\Genre;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class Search
{
    use AsAction;

    public function handle(Request $request)
    {
        $search = $request->input('search');
        $results = Genre::where('name', 'like', "%$search%")->get();

        return $results;
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }
}
