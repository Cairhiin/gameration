<?php

namespace Tests\Unit\Jobs;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;
use App\Jobs\SendNewMessageNotification;
use App\Models\Message;
use App\Notifications\MessageReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendNewMessageNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_dispatches_a_new_message_job(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $message = Message::create([
            'receiver_id' => $user->id,
            'sender_id' => $user->id,
            'subject' => 'test',
            'body' => 'test',
        ]);

        SendNewMessageNotification::dispatch($user, $message);

        Queue::assertPushed(SendNewMessageNotification::class);
    }

    public function test_it_sends_a_new_message_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Notification::send($user, new MessageReceived());

        Notification::assertSentTo(
            [$user],
            MessageReceived::class
        );
    }
}
