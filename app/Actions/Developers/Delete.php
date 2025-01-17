<?php

namespace App\Actions\Developers;

use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController()
    {
        // ...
    }

    public function authorize(): bool
    {
        return Gate::allows('developer:delete');
    }
}
