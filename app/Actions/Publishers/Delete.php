<?php

namespace App\Actions\Publishers;

use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function authorize(): bool
    {
        return Gate::allows('publisher:delete');
    }
}
