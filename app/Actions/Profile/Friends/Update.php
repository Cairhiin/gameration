<?php

namespace App\Actions\Profile\Friends;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(Request $request, User $user)
    {
        User::find(Auth::id())->pendingInvites()->updateExistingPivot($user, array('accepted' => 1), false);
    }
}
