<?php

namespace App\Jobs;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Notifications\MessageReceived;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendNewMessageNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $message;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new MessageReceived($this->message));
    }
}
