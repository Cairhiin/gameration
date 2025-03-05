<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use App\Notifications\NewFriendInvite;
use Illuminate\Support\Facades\Notification;
use App\Jobs\SendNewFriendInviteNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendNewFriendInviteNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_dispatches_a_friend_invite_job(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $friend = User::factory()->create();

        SendNewFriendInviteNotification::dispatch($user, $friend);

        Queue::assertPushed(SendNewFriendInviteNotification::class);
    }

    public function test_it_sends_a_friend_invite_notification(): void
    {
        Notification::fake();

        $friend = User::factory()->create();

        Notification::send($friend, new NewFriendInvite());

        Notification::assertSentTo(
            [$friend],
            NewFriendInvite::class
        );
    }
}
