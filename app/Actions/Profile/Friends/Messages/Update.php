<?php

namespace App\Actions\Profile\Friends\Messages;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(User $user, Message $message, Request $request)
    {
        $message->update([
            'read' => $request->read
        ]);
    }
}
