<?php

namespace App\Actions\Achievements;

use App\Models\Achievement;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Edit
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController(Achievement $achievement)
    {
        return Inertia::render('Achievements/Edit', [
            'achievement' =>  $achievement,
        ]);
    }

    public function authorize()
    {
        return Gate::allows('achievement:update');
    }
}
