<?php

namespace App\Policies;

use App\Models\Developer;
use App\Models\User;

class DeveloperPolicy
{
    public function update(User $user, Developer $developer)
    {
        return $user->id === $developer->user_id;
    }

    public function create(User $user)
    {
        return $user->isModerator();
    }

    public function delete(User $user, Developer $developer)
    {
        return $user->isAdmin();
    }
}
