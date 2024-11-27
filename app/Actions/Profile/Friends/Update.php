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
        if ($request->accepted) {
            User::findOrFail(Auth::id())->pendingInvites()->updateExistingPivot($user, array('accepted' => 1), false);
        } else {
            User::findOrFail(Auth::id())->pendingInvites()->detach($user->id);
        }
    }
}
