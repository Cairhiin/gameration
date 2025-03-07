<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_model_can_create_a_message(): void
    {
        $message = Message::create([
            'receiver_id' => User::factory()->create()->id,
            'sender_id' => User::factory()->create()->id,
            'subject' => 'test',
            'body' => 'test',
            'read' => false,
            'archived' => false
        ]);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'receiver_id' => $message->receiver_id,
            'sender_id' => $message->sender_id,
            'subject' => 'test',
            'body' => 'test',
            'read' => false,
            'archived' => false
        ]);
    }

    public function test_message_model_can_retrieve_a_message(): void
    {
        Message::create([
            'receiver_id' => User::factory()->create()->id,
            'sender_id' => User::factory()->create()->id,
            'subject' => 'test',
            'body' => 'test',
            'read' => false,
            'archived' => false
        ]);

        $message = Message::first();

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals($message->subject, 'test');
        $this->assertEquals($message->body, 'test');
        $this->assertEquals($message->read, false);
        $this->assertEquals($message->archived, false);
        $this->assertEquals($message->receiver_id, $message->receiver->id);
        $this->assertEquals($message->sender_id, $message->sender->id);
    }

    public function test_message_model_has_sender_relationship(): void
    {
        $user = User::factory()->create();

        $message = Message::create([
            'receiver_id' => User::factory()->create()->id,
            'sender_id' => $user->id,
            'subject' => 'test',
            'body' => 'test'
        ]);

        $this->assertInstanceOf(User::class, $message->sender);
        $this->assertEquals($user->id, $message->sender->id);
    }
}
