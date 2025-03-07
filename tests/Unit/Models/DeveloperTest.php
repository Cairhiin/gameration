<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Game;
use App\Models\User;
use App\Models\Developer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeveloperTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_model_can_create_developer(): void
    {
        $developer = Developer::factory()->create([
            'name' => 'Rockstar Games',
            'country' => 'USA',
            'city' => 'New York',
            'year' => 1998,
            'user_id' => User::factory()->create()
        ]);

        $this->assertDatabaseHas('developers', ['id' => $developer->id, 'name' => 'Rockstar Games', 'country' => 'USA', 'city' => 'New York', 'year' => 1998]);
    }

    public function test_developer_model_can_retrieve_a_developer(): void
    {
        Developer::factory()->create([
            'name' => 'Rockstar Games',
            'country' => 'USA',
            'city' => 'New York',
            'year' => 1998,
            'user_id' => User::factory()->create()
        ]);
        $developer = Developer::first();

        $this->assertEquals($developer->name, 'Rockstar Games');
        $this->assertEquals($developer->country, 'USA');
        $this->assertEquals($developer->city, 'New York');
        $this->assertEquals($developer->year, 1998);
        $this->assertInstanceOf(User::class, $developer->user);
    }

    public function test_developer_model_has_user_relationship(): void
    {
        $developer = Developer::factory()->create(['user_id' => User::factory()->create()]);

        $this->assertInstanceOf(User::class, $developer->user);
    }

    public function test_developer_model_has_games_relationship(): void
    {
        $developer = Developer::factory()->create(['user_id' => User::factory()->create()]);
        Game::factory()->create(['developer_id' => $developer->id]);

        $this->assertInstanceOf(Collection::class, $developer->games);
        $this->assertInstanceOf(Game::class, $developer->games->first());
    }

    public function test_developer_model_appends_games_count_attribute(): void
    {
        $developer = Developer::factory()->create(['user_id' => User::factory()->create()]);
        Game::factory()->count(3)->create(['developer_id' => $developer->id]);

        $this->assertEquals(3, $developer->games_count);
    }

    public function test_developer_model_appends_avg_rating_attribute(): void
    {
        $developer = Developer::factory()->create(['user_id' => User::factory()->create()]);
        $games = Game::factory()->count(4)->create(['developer_id' => $developer->id]);

        $games->each(function ($game, $index) {
            $game->users()->attach(User::factory()->create(), ['rating' => $index]); // 0, 1, 2, 3 (avg = 2 ignoring 0)
        });

        $this->assertEquals(2, $developer->avg_rating);
    }
}
