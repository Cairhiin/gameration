<?php

namespace Tests\Unit\Models;

use App\Models\Game;
use App\Models\User;
use App\Models\Publisher;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublisherTest extends TestCase
{
    use RefreshDatabase;

    public function test_publisher_model_can_create_publisher(): void
    {
        $publisher = Publisher::factory()->create([
            'name' => 'Rockstar Games',
            'country' => 'USA',
            'city' => 'New York',
            'year' => 1998,
            'user_id' => User::factory()->create()
        ]);

        $this->assertDatabaseHas('publishers', ['id' => $publisher->id, 'name' => 'Rockstar Games', 'country' => 'USA', 'city' => 'New York', 'year' => 1998]);
    }

    public function test_publisher_model_can_retrieve_a_publisher(): void
    {
        Publisher::factory()->create([
            'name' => 'Rockstar Games',
            'country' => 'USA',
            'city' => 'New York',
            'year' => 1998,
            'user_id' => User::factory()->create()
        ]);
        $publisher = Publisher::first();

        $this->assertEquals($publisher->name, 'Rockstar Games');
        $this->assertEquals($publisher->country, 'USA');
        $this->assertEquals($publisher->city, 'New York');
        $this->assertEquals($publisher->year, 1998);
        $this->assertInstanceOf(User::class, $publisher->user);
    }

    public function test_publisher_model_has_user_relationship(): void
    {
        $publisher = Publisher::factory()->create(['user_id' => User::factory()->create()]);

        $this->assertInstanceOf(User::class, $publisher->user);
    }

    public function test_publisher_model_has_games_relationship(): void
    {
        $publisher = Publisher::factory()->create(['user_id' => User::factory()->create()]);
        Game::factory()->create(['publisher_id' => $publisher->id]);

        $this->assertInstanceOf(Collection::class, $publisher->games);
        $this->assertInstanceOf(Game::class, $publisher->games->first());
    }

    public function test_publisher_model_appends_games_count_attribute(): void
    {
        $publisher = Publisher::factory()->create(['user_id' => User::factory()->create()]);
        Game::factory()->count(3)->create(['publisher_id' => $publisher->id]);

        $this->assertEquals(3, $publisher->games_count);
    }

    public function test_publisher_model_appends_avg_rating_attribute(): void
    {
        $publisher = Publisher::factory()->create(['user_id' => User::factory()->create()]);
        $games = Game::factory()->count(4)->create(['publisher_id' => $publisher->id]);

        $games->each(function ($game, $index) {
            $game->users()->attach(User::factory()->create(), ['rating' => $index]); // 0, 1, 2, 3 (avg = 2 ignoring 0)
        });

        $this->assertEquals(2, $publisher->avg_rating);
    }
}
