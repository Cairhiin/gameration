<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_achievement_model_can_create_achievement(): void
    {
        $achievement = Achievement::create([
            'name' => 'test',
            'description' => 'lorem ipsum',
            'points' => 10,
            'image' => 'image.jpg',
        ]);

        $this->assertDatabaseHas('achievements', ['id' => $achievement->id, 'name' => 'test', 'points' => 10, 'image' => 'image.jpg']);
    }

    public function test_achievement_model_can_retrieve_a_achievement(): void
    {
        Achievement::create([
            'name' => 'test',
            'description' => 'lorem ipsum',
            'points' => 10,
            'image' => 'image.jpg',
        ]);
        $achievement = Achievement::first();

        $this->assertEquals($achievement->name, 'test');
        $this->assertEquals($achievement->description, 'lorem ipsum');
        $this->assertEquals($achievement->points, 10);
        $this->assertEquals($achievement->image, 'image.jpg');
    }

    public function test_achievement_model_has_user_relationship(): void
    {
        $achievement = Achievement::create([
            'name' => 'test',
            'description' => 'lorem ipsum',
            'points' => 10,
            'image' => 'image.jpg',
        ]);

        $achievement->user()->attach(User::factory()->create());

        $this->assertInstanceOf(User::class, $achievement->user->first());
    }
}
