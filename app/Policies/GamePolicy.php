<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;

class GamePolicy
{
    public function update(User $user, Game $game)
    {
        return ($user->id === $game->user_id && $user->isModerator()) || $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isModerator() || $user->isAdmin();
    }

    public function delete(User $user, Game $game)
    {
        return $user->isAdmin();
    }
}
