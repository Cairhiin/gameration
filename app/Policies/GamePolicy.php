<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;

class GamePolicy
{
    public function update(User $user, Game $game)
    {
        return $user->id === $game->user_id;
    }

    public function create(User $user, Game $game)
    {
        return $user->isModerator();
    }

    public function delete(User $user, Game $game)
    {
        return $user->isAdmin();
    }
}
