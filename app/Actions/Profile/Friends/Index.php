<?php

namespace App\Actions\Profile\Friends;

use App\Models\Message;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle()
    {
        $friends = User::findOrFail(Auth::id())->friends;
        $newestMessages = Message::where('receiver_id', Auth::id())->limit(5)->get();

        return Inertia::render('Profile/Friends/Index', [
            'friends' => $friends,
            'pendingFriends' => auth()->user()->pendingFriends,
            'pendingInvites' => auth()->user()->pendingInvites,
            'newestMessages' => $newestMessages
        ]);
    }
}
