<?php

namespace App\Actions\Books\Narrators;

use App\Models\Person;
use App\Traits\HasSearch;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class Search
{
    use AsAction;
    use HasSearch;

    public function handle(Request $request): ?Collection
    {
        return $this->search(Person::class, $request->input('search'), 'name', 5, [
            'type' => 'narrator',
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
