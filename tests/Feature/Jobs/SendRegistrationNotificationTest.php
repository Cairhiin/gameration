<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use App\Notifications\MessageReceived;
use App\Jobs\SendRegistrationNotification;
use App\Notifications\NewUserWelcome;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendRegistrationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_dispatches_a_registration_notification_job(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        SendRegistrationNotification::dispatch($user);

        Queue::assertPushed(SendRegistrationNotification::class);
    }

    public function test_it_sends_a_registration_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Notification::send($user, new NewUserWelcome());

        Notification::assertSentTo(
            [$user],
            NewUserWelcome::class
        );
    }
}
