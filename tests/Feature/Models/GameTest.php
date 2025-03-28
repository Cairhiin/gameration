<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Game;
use App\Models\User;
use App\Models\Genre;
use App\Models\GameUser;
use App\Models\Developer;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_model_can_create_a_game(): void
    {
        $game = Game::factory([
            'user_id' => User::factory()->create(),
            'publisher_id' => Publisher::factory()->create(),
            'developer_id' => Developer::factory()->create(),
            'image' => 'test.jpg',
            'name' => 'Test Game',
            'description' => 'Test Description',
            'released_at' => '2021-01-01',
        ])->create();

        $this->assertDatabaseHas('games', ['id' => $game->id, 'name' => 'Test Game', 'description' => 'Test Description', 'released_at' => '2021-01-01']);
    }

    public function test_game_model_can_retrieve_a_game(): void
    {
        Game::factory([
            'user_id' => User::factory()->create(),
            'publisher_id' => Publisher::factory()->create(),
            'developer_id' => Developer::factory()->create(),
            'image' => 'test.jpg',
            'name' => 'Test Game',
            'description' => 'Test Description',
            'released_at' => '2021-01-01',
        ])->create();

        $game = Game::first();

        $this->assertEquals($game->name, 'Test Game');
        $this->assertEquals($game->description, 'Test Description');
        $this->assertEquals($game->released_at, '2021-01-01');
        $this->assertInstanceOf(User::class, $game->creator);
        $this->assertInstanceOf(Publisher::class, $game->publisher);
        $this->assertInstanceOf(Developer::class, $game->developer);
        $this->assertEquals($game->image, 'test.jpg');
    }

    public function test_game_model_has_developer_relationship(): void
    {
        $user = User::factory()->create();
        $game = Game::factory(['user_id' => $user->id])->create();

        $developer = $game->developer;

        $this->assertInstanceOf(Developer::class, $developer);
    }

    public function test_game_model_has_publisher_relationship(): void
    {
        $user = User::factory()->create();
        $game = Game::factory(['user_id' => $user->id])->create();

        $publisher = $game->publisher;

        $this->assertInstanceOf(Publisher::class, $publisher);
    }

    public function test_game_model_has_genres(): void
    {
        $user = User::factory()->create();
        $game = Game::factory(['user_id' => $user->id])->has(Genre::factory())->create();

        $genres = $game->genres;

        $this->assertInstanceOf(Collection::class, $genres);
        $this->assertInstanceOf(Genre::class, $genres->first());
    }

    public function test_game_model_has_a_creator(): void
    {
        $user = User::factory()->has(Game::factory())->create();
        $game = Game::factory(['user_id' => $user->id])->create();

        $creator = $game->creator;

        $this->assertInstanceOf(User::class, $creator);
    }


    public function test_game_model_has_ratings(): void
    {
        $user = User::factory()->create();
        $game = Game::factory(['user_id' => $user->id])->create();
        $game->users()->attach($user, ['rating' => 1]);

        $ratings = $game->users;

        $this->assertInstanceOf(Collection::class, $ratings);
    }

    public function test_game_model_avg_rating_return_the_right_value(): void
    {
        foreach ([1, 2, 3] as $user) {
            $users[] = User::factory()->create();
        }

        $game = Game::factory(['user_id' => $users[0]->id])->create();

        foreach ($users as $key => $value) {
            // Attach the user to the game with a rating: 1, 2, 3
            $game->users()->attach($value, ['rating' => $key + 1]);
        }

        $this->assertEquals(2, $game->calculateGameRating());

        foreach ($users as $key => $value) {
            // Attach the user to the game with a rating: 0, 2, 4
            $game->users()->updateExistingPivot($value, ['rating' => $key * 2]);
        }

        $this->assertEquals(3, $game->calculateGameRating());
    }

    public function test_game_model_median_rating_returns_the_right_value(): void
    {
        foreach ([1, 2, 3, 4] as $user) {
            $users[] = User::factory()->create();
        }

        $game = Game::factory(['user_id' => $users[0]->id])->create();

        foreach ($users as $key => $value) {
            // Attach the user to the game with a rating: 1, 2, 3, 4
            $game->users()->attach($value, ['rating' => $key + 1]);
        }

        $this->assertEquals(2.5, $game->calculateMedianRating());

        foreach ($users as $key => $value) {
            // Attach the user to the game with a rating: 0, 1, 2, 3
            $game->users()->updateExistingPivot($value, ['rating' => $key]);
        }

        $this->assertEquals(2, $game->calculateGameRating());
    }

    public function test_game_model_rating_count_returns_the_right_value(): void
    {
        foreach ([1, 2, 3, 4] as $rating) {
            $users[] = User::factory()->create();
        }

        $game = Game::factory(['user_id' => $users[0]->id])->create();

        foreach ($users as $key => $value) {
            // Attach the user to the game with a rating: 1, 2, 3, 4
            $game->users()->attach($value, ['rating' => $key + 1]);
        }

        $this->assertEquals(4, $game->calculateNumberOfRatings());
    }

    public function test_game_model_average_rating_is_calculated_properly_if_a_rating_is_zero(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $game = Game::factory(['user_id' => $user1->id])->create();

        $game->users()->attach($user1, ['rating' => 0]);
        $game->users()->attach($user2, ['rating' => 1]);

        $this->assertEquals(1, $game->calculateGameRating());
    }

    public function test_game_model_median_rating_is_calculated_properly_if_a_rating_is_zero(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $game = Game::factory(['user_id' => $user1->id])->create();

        $game->users()->attach($user1, ['rating' => 0]);
        $game->users()->attach($user2, ['rating' => 1]);

        $this->assertEquals(1, $game->calculateMedianRating());
    }

    public function test_game_model_count_is_calculated_properly_if_a_rating_is_zero(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $game = Game::factory(['user_id' => $user1->id])->create();

        $game->users()->attach($user1, ['content' => 'test']); // Rating is 0 by default
        $game->users()->attach($user2, ['rating' => 1]);

        $this->assertEquals(1, $game->calculateNumberOfRatings());
    }
}
