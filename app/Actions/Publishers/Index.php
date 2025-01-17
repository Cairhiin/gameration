<?php

namespace App\Actions\Publishers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Publisher;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class Index
{
    use AsAction;

    public function handle(): ?Collection
    {
        return Publisher::all();
    }

    public function asController(): Response
    {
        $publishers = $this->handle();

        return Inertia::render('Publishers/Index', [
            'publishers' => $publishers
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('publisher:viewAny');
    }
}
