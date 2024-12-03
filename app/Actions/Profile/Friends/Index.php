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
            'messages' => \App\Actions\Profile\Friends\Messages\GetNewestMessages::run($request),
            'friends' => User::findOrFail(Auth::id())->friends->sortBy('username')->values()->all(),
            'pendingFriends' => auth()->user()->pendingFriends->sortBy('username')->values()->all(),
            'pendingInvites' => auth()->user()->pendingInvites->sortBy('username')->values()->all(),
        ]);
    }
}
