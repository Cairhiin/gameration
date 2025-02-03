<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

trait HasFriendList
{
    // Map through the friends collection and swap the pivot values if the friend_id is the current user's ID
    // This is done to keep the API consistent and avoid confusion when displaying the friends list
    public function formattedFriendList($friends): Collection
    {
        return $friends->map(function ($friend) {
            if ($friend->pivot->friend_id === Auth::id()) {
                // Swap the pivot values so that the friend_id is the current user's ID
                return [
                    ...$friend->toArray(),
                    'pivot' => [
                        ...$friend->pivot->toArray(),
                        'friend_id' => $friend->pivot->user_id,
                        'user_id' => $friend->pivot->friend_id
                    ]
                ];
            } else {
                // Return the friend as is
                return $friend;
            }
        });
    }
}
