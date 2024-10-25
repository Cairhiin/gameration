<?php

namespace App\Actions\Profile\Friends\Messages;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(Message $message)
    {
        $deleted = false;

        try {
            DB::beginTransaction();
            if (($message->sender_id == Auth::id() && $message->read == 0) || $message->receiver_id == Auth::id()) {
                $deleted = $message->delete();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } finally {
            DB::commit();
        }

        return $deleted;
    }

    public function asController(User $user, Message $message): RedirectResponse
    {
        $success = $this->handle($message);

        if ($success) {
            return Redirect::route("profile.friends.index")->with("message", "Your message was deleted.");
        } else {
            return Redirect::route("profile.friends.index")->with("message", "Failed to delete the message!");
        }
    }
}
