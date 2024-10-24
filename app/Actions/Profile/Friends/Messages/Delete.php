<?php

namespace App\Actions\Profile\Friends\Messages;

use App\Models\User;
use App\Models\Message;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(User $user, Message $message)
    {
        dd($message);
    }
}
