<?php

namespace App\Actions\Profile\Friends;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(User $user)
    {
        try {
            DB::beginTransaction();

            $owner = User::findOrFail(Auth::id());
            $owner->friendsOfMine()->detach($user->id);
            $owner->friendOf()->detach($user->id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        } finally {
            DB::endTransaction();
        }
    }

    public function asController(User $user): RedirectResponse
    {
        $result = $this->handle($user);

        if ($result) {
            return Redirect::route("profile.friends.index", Auth::user())->with("message", "Friend has been removed.");
        } else {
            return Redirect::route("profile.friends.index", Auth::user())->with("message", "Failed to remove friend!");
        }
    }
}
