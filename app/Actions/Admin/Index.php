<?php

namespace App\Actions\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle(): Response
    {
        return Inertia::render('Admin/Index');
    }
}
