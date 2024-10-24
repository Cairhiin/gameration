<?php

namespace App\Actions\Profile\Friends\Messages;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSent
{
    use AsAction;

    public function handle(User $user)
    {
        return Message::where('sender_id', Auth::user()->id)->where('receiver_id', $user->id)->orderBy('created_at', 'desc')->with('receiver')->paginate();
    }
}
