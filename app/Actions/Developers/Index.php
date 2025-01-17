<?php

namespace App\Actions\Developers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Developer;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class Index
{
    use AsAction;

    public function handle(): ?Collection
    {
        return Developer::all();
    }

    public function asController(): Response
    {
        $developers = $this->handle();

        return Inertia::render('Developers/Index', [
            'developers' => $developers
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('developer:viewAny');
    }
}
