<?php

namespace App\Actions\Publishers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class Search
{
    use AsAction;

    public function handle(Request $request)
    {
        $search = $request->input('search');
        $results = Publisher::where('name', 'like', "%$search%")->get();

        return $results;
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }
}
