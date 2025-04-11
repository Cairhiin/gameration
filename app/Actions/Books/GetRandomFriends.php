<?php

namespace App\Actions\Books;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetRandomFriends
{
    use AsAction;

    public function handle(User $user): Collection
    {
        $friends = $user->friends;

        $loadedFriends = new Collection();

        if ($friends->count() === 1 && $friends->first()->books()->exists()) {
            $loadedFriends = $friends->load('game_user');
        }

        if ($friends->count() > 1) {
            $loadedFriends = $friends->where(function ($friend) {
                return $friend->books()->exists();
            });

            if ($loadedFriends->count() > 2) {
                $loadedFriends = $loadedFriends->random(2);
            } else {
                $loadedFriends = $loadedFriends->take(2);
            }
        }

        return $loadedFriends->map(function ($friend) {
            $friend->books = $friend->books()->latest()->limit(5)->get();
            return $friend;
        });
    }
}
