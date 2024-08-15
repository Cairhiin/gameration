<?php

namespace App\Policies;

use App\Models\Publisher;
use App\Models\User;

class PublisherPolicy
{
    public function update(User $user, Publisher $publisher)
    {
        return ($user->id === $publisher->user_id && $user->isModerator()) || $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isModerator() || $user->isAdmin();
    }

    public function delete(User $user, Publisher $publisher)
    {
        return $user->isAdmin();
    }
}
