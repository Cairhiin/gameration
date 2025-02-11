<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class Search
{
    use AsAction;

    public function handle(Request $request): ?Collection
    {
        $search = $request->input('search');
        $results = User::where('username', 'like', "%$search%")->get()->take(5);

        return $results;
    }

    public function asController(Request $request): ?Collection
    {
        return $this->handle($request);
    }

    public function authorize(): bool
    {
        return Gate::allows('user:viewAny');
    }
}
