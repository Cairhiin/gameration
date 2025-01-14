<?php

namespace App\Actions\Games;

use App\Models\Game;
use App\Traits\HasSearch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class Search
{
    use AsAction;
    use HasSearch;

    public function handle(Request $request): ?Collection
    {
        return $this->search(Game::class, $request->input('search'));
    }

    public function asController(Request $request): ?Collection
    {
        return $this->handle($request);
    }
}
