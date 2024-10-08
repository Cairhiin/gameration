<?php

namespace App\Policies;

use App\Models\Genre;
use App\Models\User;

class GenrePolicy
{
    public function update(User $user, Genre $genre)
    {
        return $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Genre $genre)
    {
        return $user->isAdmin();
    }
}
