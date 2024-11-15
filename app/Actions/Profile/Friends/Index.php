<?php

namespace App\Actions\Profile\Friends;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle(Request $request)
    {
        return Inertia::render('Profile/Friends/Index', [
            'friends' => User::findOrFail(Auth::id())->friends,
            'messages' => \App\Actions\Profile\Friends\Messages\GetNewestMessages::run($request),
            'pendingFriends' => auth()->user()->pendingFriends,
            'pendingInvites' => auth()->user()->pendingInvites,
        ]);
    }
}
