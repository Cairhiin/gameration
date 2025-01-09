<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Notifications\NewFriendInvite;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendNewFriendInviteNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $friend;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, User $friend)
    {
        $this->user = $user;
        $this->friend = $friend;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new NewFriendInvite($this->friend));
    }
}
