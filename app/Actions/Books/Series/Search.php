<?php

namespace App\Actions\Books\Series;

use App\Models\Book;
use App\Models\Series;
use App\Traits\HasSearch;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class Search
{
    use AsAction;
    use HasSearch;

    public function handle(Request $request): ?Collection
    {
        return $this->search(Series::class, $request->input('search'));
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
