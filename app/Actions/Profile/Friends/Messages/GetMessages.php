<?php

namespace App\Actions\Profile\Friends\Messages;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetMessages
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function asController(User $user): array
    {
        $inbox = \App\Actions\Profile\Friends\Messages\GetInbox::run($user);
        $sent = \App\Actions\Profile\Friends\Messages\GetSent::run($user);

        return [
            'inbox' => $inbox,
            'sent' => $sent
        ];
    }
}
