<?php

namespace App\Actions\Profile\Friends;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(Request $request): ?User
    {
        try {
            DB::beginTransaction();

            $friend = User::where("username", $request->username)->first();
            $user = User::find(Auth::id());

            if (!$friend || $user->id === $friend->id) {
                return null;
            }

            $user->friendsOfMine()->attach($friend->id, [
                'accepted' => false
            ]);

            DB::commit();

            return $friend;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        $friend = $this->handle($request);

        if ($friend) {
            return Redirect::route("profile.friends.index", Auth::user())->with("message", "Friend invite has been sent.");
        } else {
            return Redirect::route("profile.friends.index", Auth::user())->with("message", "Failed to send friend invite!");
        }
    }

    /**
     * Returns an array of validation rules for the `username` field.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'min:3'],
        ];
    }

    /**
     * Returns the validation error bag for the 'addFriend' validation.
     *
     * @return string The validation error bag name.
     */
    public function getValidationErrorBag(): string
    {
        return 'addFriend';
    }

    /**
     * Returns an array of validation messages for the 'username' field.
     *
     * @return array An associative array where the keys are the validation rules and the values are the corresponding error messages.
     */
    public function getValidationMessages(): array
    {
        return [
            'username.required' => 'Looks like you forgot to add a user.',
        ];
    }
}
