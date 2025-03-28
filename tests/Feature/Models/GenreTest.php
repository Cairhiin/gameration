<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Game;
use App\Models\User;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreTest extends TestCase
{
    use RefreshDatabase;

    public function test_genre_model_can_create_genre(): void
    {
        $genre = Genre::factory()->create([
            'name' => 'Action',
            'description' => 'Action games are games that emphasize physical challenges.',
        ]);

        $this->assertDatabaseHas('genres', ['id' => $genre->id, 'name' => 'Action', 'description' => 'Action games are games that emphasize physical challenges.']);
    }

    public function test_genre_model_can_retrieve_a_genre(): void
    {
        Genre::factory()->create([
            'name' => 'Action',
            'description' => 'Action games are games that emphasize physical challenges.',
        ]);

        $genre = Genre::first();

        $this->assertEquals($genre->name, 'Action');
        $this->assertEquals($genre->description, 'Action games are games that emphasize physical challenges.');
    }

    public function test_genre_model_appends_games_count(): void
    {
        $genre = Genre::factory()->create();
        $games = Game::factory()->count(5)->create();

        foreach ($games as $game) {
            $game->genres()->attach($genre);
        }

        $this->assertEquals(5, $genre->games_count);
    }

    public function test_genre_model_appends_avg_rating(): void
    {
        $genre = Genre::factory()->create();
        $games = Game::factory()->count(5)->create();

        foreach ($games as $key => $game) {
            $game->users()->attach(User::factory()->create(), ['rating' => $key]); // 0, 1, 2, 3, 4 => avg 2.5 (ignoring 0 because it's unrated)
            $game->genres()->attach($genre);
        }

        $this->assertEquals(2.5, $genre->avg_rating);
    }

    public function test_genre_model_has_games_relationship(): void
    {
        $genre = Genre::factory()->create();
        $game = Game::factory()->create();

        $game->genres()->attach($genre);

        $this->assertInstanceOf(Game::class, $genre->games()->first());
    }

    public function test_genre_model_has_games_by_rating_collection(): void
    {
        $genre = Genre::factory()->create();
        $games = Game::factory()->count(5)->create();

        foreach ($games as $key => $game) {
            $game->users()->attach(User::factory()->create(), ['rating' => $key]); // 0, 1, 2, 3, 4 => avg 2.5 (ignoring 0 because it's unrated)
            $game->genres()->attach($genre);
        }

        $this->assertInstanceOf(Game::class, $genre->gamesByRating()->first());
        $this->assertEquals(4, $genre->gamesByRating()->first()->users()->first()->pivot->rating);
        $this->assertEquals(0, $genre->gamesByRating()->last()->users()->first()->pivot->rating);
    }

    public function test_genre_model_has_games_by_date_relationship(): void
    {
        $genre = Genre::factory()->create();
        $game = Game::factory()->create();

        $game->genres()->attach($genre);

        $this->assertInstanceOf(Game::class, $genre->gamesByDate()->first());
    }
}
