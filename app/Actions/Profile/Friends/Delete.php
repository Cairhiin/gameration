<?php

namespace App\Actions\Profile\Friends;

use App\Models\User;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(Request $request, User $user)
    {
        dd($user);
    }
}
