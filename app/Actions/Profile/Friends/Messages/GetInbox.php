<?php

namespace App\Actions\Profile\Friends\Messages;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetInbox
{
    use AsAction;

    public function handle(User $user)
    {
        return Message::where('receiver_id', Auth::id())->where('sender_id', $user->id)->orderBy('created_at', 'desc')->with('sender')->paginate();
    }
}
