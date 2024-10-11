<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return Auth::id() === $user->id;
    }

    public function view(User $user): bool
    {
        return Auth::id() === $user->id;
    }

    public function create(User $user): bool
    {
        return Auth::id() === $user->id;
    }

    public function update(User $user, User $friend): bool
    {
        return $user->pendingInvites->contains('username', $friend->username) && Auth::id() === $user->id;
    }

    public function delete(User $user, User $friend): bool
    {
        return $user->pendingInvites->contains('username', $friend->username) && Auth::id() === $user->id;
    }
}
