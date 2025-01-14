<?php

namespace App\Actions\Publishers;

use App\Models\Publisher;
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
        return $this->search(Publisher::class, $request->input('search'));
    }

    public function asController(Request $request): ?Collection
    {
        return $this->handle($request);
    }
}
