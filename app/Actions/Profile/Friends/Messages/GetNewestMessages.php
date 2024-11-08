<?php

namespace App\Actions\Profile\Friends\Messages;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetNewestMessages
{
    use AsAction;

    public function handle(): array
    {
        $inbox = Message::where('receiver_id', Auth::id())->orderBy('created_at', 'desc')->with('sender')->take(10)->get();
        $sent = Message::where('sender_id', Auth::user()->id)->orderBy('created_at', 'desc')->with('receiver')->take(10)->get();

        return [
            'inbox' => $inbox,
            'sent' => $sent
        ];
    }
}
