<?php

namespace App\Actions\Profile\Friends;

use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle()
    {
        $friends = auth()->user()->friends;

        return Inertia::render('Profile/Friends/Index', [
            'friends' => $friends,
            'pendingFriends' => auth()->user()->pendingFriends,
            'pendingInvites' => auth()->user()->pendingInvites
        ]);
    }
}
