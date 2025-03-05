<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Game;
use App\Models\User;
use App\Models\Developer;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_has_a_developer(): void
    {
        $user = User::factory()->create();
        $game = Game::factory(['user_id' => $user->id])->create();

        $developer = $game->developer;

        $this->assertInstanceOf(Developer::class, $developer);
    }

    public function test_game_has_a_publisher(): void
    {
        $user = User::factory()->create();
        $game = Game::factory(['user_id' => $user->id])->create();

        $publisher = $game->publisher;

        $this->assertInstanceOf(Publisher::class, $publisher);
    }

    public function test_game_has_genres(): void
    {
        $user = User::factory()->create();
        $game = Game::factory(['user_id' => $user->id])->create();

        $genres = $game->genres;

        $this->assertInstanceOf(Collection::class, $genres);
    }

    public function test_game_has_a_creator(): void
    {
        $user = User::factory()->create();
        $game = Game::factory(['user_id' => $user->id])->create();

        $creator = $game->creator;

        $this->assertInstanceOf(User::class, $creator);
    }


    public function test_game_has_ratings(): void
    {
        $user = User::factory()->create();
        $game = Game::factory(['user_id' => $user->id])->create();

        $ratings = $game->users;

        $this->assertInstanceOf(Collection::class, $ratings);
    }

    public function test_game_avg_rating_return_the_right_value(): void
    {
        foreach ([1, 2, 3] as $rating) {
            $user[] = User::factory()->create();
        }

        $game = Game::factory(['user_id' => $user[0]->id])->create();

        foreach ($user as $key => $value) {
            // Attach the user to the game with a rating: 1, 2, 3
            $game->users()->attach($value, ['rating' => $key + 1]);
        }

        $this->assertEquals(2, $game->calculateGameRating());
    }

    public function test_game_median_rating_returns_the_right_value(): void
    {
        foreach ([1, 2, 3, 4] as $rating) {
            $user[] = User::factory()->create();
        }

        $game = Game::factory(['user_id' => $user[0]->id])->create();

        foreach ($user as $key => $value) {
            // Attach the user to the game with a rating: 1, 2, 3, 4
            $game->users()->attach($value, ['rating' => $key + 1]);
        }

        $this->assertEquals(2.5, $game->calculateMedianRating());
    }

    public function test_game_rating_count_returns_the_right_value(): void
    {
        foreach ([1, 2, 3, 4] as $rating) {
            $user[] = User::factory()->create();
        }

        $game = Game::factory(['user_id' => $user[0]->id])->create();

        foreach ($user as $key => $value) {
            // Attach the user to the game with a rating: 1, 2, 3, 4
            $game->users()->attach($value, ['rating' => $key + 1]);
        }

        $this->assertEquals(4, $game->calculateNumberOfRatings());
    }

    public function test_game_average_rating_is_calculated_properly_if_a_rating_is_zero(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $game = Game::factory(['user_id' => $user1->id])->create();

        $game->users()->attach($user1, ['rating' => 0]);
        $game->users()->attach($user2, ['rating' => 1]);

        $this->assertEquals(1, $game->calculateGameRating());
    }

    public function test_game_median_rating_is_calculated_properly_if_a_rating_is_zero(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $game = Game::factory(['user_id' => $user1->id])->create();

        $game->users()->attach($user1, ['rating' => 0]);
        $game->users()->attach($user2, ['rating' => 1]);

        $this->assertEquals(1, $game->calculateMedianRating());
    }

    public function test_game_count_is_calculated_properly_if_a_rating_is_zero(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $game = Game::factory(['user_id' => $user1->id])->create();

        $game->users()->attach($user1, ['rating' => 0]);
        $game->users()->attach($user2, ['rating' => 1]);

        $this->assertEquals(1, $game->calculateNumberOfRatings());
    }
}
