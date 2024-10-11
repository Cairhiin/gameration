<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class Search
{
    use AsAction;

    public function handle(Request $request)
    {
        $search = $request->input('search');
        $results = User::where('username', 'like', "%$search%")->get()->take(5);

        return $results;
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }
}
