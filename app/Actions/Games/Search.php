<?php

namespace App\Actions\Games;

use App\Models\Game;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class Search
{
    use AsAction;

    public function handle(Request $request)
    {
        $search = $request->input('search');
        $results = Game::where('name', 'like', "%$search%")->get()->take(5);

        return $results;
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }
}
