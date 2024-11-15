<?php

namespace App\Actions\Profile\Friends\Messages;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetNewestMessages
{
    use AsAction;

    public function handle(Request $request): array
    {
        $inbox = Message::where('receiver_id', Auth::id());
        $sent = Message::where('sender_id', Auth::user()->id);

        if ($request->id) {
            $inbox = $inbox->where('sender_id', $request->id);
            $sent = $sent->where('receiver_id', $request->id);
        }

        $inbox = $inbox->orderBy('created_at', 'desc')->with('sender')->paginate(2);
        $sent = $sent->orderBy('created_at', 'desc')->with('receiver')->paginate(2);

        return [
            'inbox' => $inbox,
            'sent' => $sent
        ];
    }
}
