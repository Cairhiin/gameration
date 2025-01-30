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
        $friends = User::findOrFail(Auth::id())->friends()->sortBy('username');

        $formattedFriends = $friends->map(function ($friend) {
            if ($friend->pivot->friend_id === Auth::id()) {
                return [
                    ...$friend->toArray(),
                    'pivot' => [
                        ...$friend->pivot->toArray(),
                        'friend_id' => $friend->pivot->user_id,
                        'user_id' => $friend->pivot->friend_id
                    ]
                ];
            } else {
                return $friend;
            }
        });

        return Inertia::render('Profile/Friends/Index', [
            'messages' => \App\Actions\Profile\Friends\Messages\GetNewestMessages::run($request),
            'friends' => $formattedFriends->values()->all(),
            'pendingFriends' => auth()->user()->pendingFriends->sortBy('username')->values()->all(),
            'pendingInvites' => auth()->user()->pendingInvites->sortBy('username')->values()->all(),
        ]);
    }
}
