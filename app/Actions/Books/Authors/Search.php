<?php

namespace App\Actions\Books\Authors;

use App\Models\Person;
use App\Traits\HasSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class Search
{
    use AsAction;
    use HasSearch;

    public function handle(Request $request): ?Collection
    {
        return $this->search(Person::class, $request->input('search'), 'name', 5, [
            'type' => 'author',
        ]);
    }

    public function asController(Request $request): ?Collection
    {
        return $this->handle($request);
    }

    public function authorize(): bool
    {
        return Gate::allows('book:viewAny');
    }
}
