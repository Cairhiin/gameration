<?php

namespace Tests\Feature\Actions\Genres;

use Tests\TestCase;
use App\Models\Genre;
use App\Traits\HasTestFunctions;
use App\Traits\HasSeededDatabase;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;

class ShowTest extends TestCase
{
    use HasSeededDatabase;
    use HasTestFunctions;

    private Genre $genre;
    private Collection $games;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->genre = Genre::create([
            'name' => "test",
        ]);

        $this->games = $this->createGames(3);

        foreach ($this->games as $index => $game) {
            $game->genres()->sync([$this->genre->id]);
            $game = $this->rateGame($game, $index * 2);
            $game->save();
        }
    }

    public function test_genres_show_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres/' . $this->genre->id);

        $response->assertStatus(200);
    }

    public function test_genres_show_page_returns_an_inertia_view(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres/' . $this->genre->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Genres/Show')
                ->has(
                    'genre',
                    fn(Assert $page) => $page
                        ->where('id', $this->genre->id)
                        ->where('name', $this->genre->name)
                        ->has('games_count')
                        ->has('avg_rating')
                        ->has('created_at')
                        ->has('updated_at')
                )
        );
    }

    public function test_genres_show_page_contains_the_genre_and_a_list_of_paginated_games(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres/' . $this->genre->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Genres/Show')
                ->has(
                    'games',
                    fn(Assert $page) => $page
                        ->has(
                            'data.0',
                            fn(Assert $page) => $page
                                ->has('id')
                                ->has('name')
                                ->etc()
                        )
                        ->has('first_page_url')
                        ->etc()
                        ->has('links')
                )
        );
    }

    public function test_genres_show_page_shows_the_correct_average_rating(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres/' . $this->genre->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Genres/Show')
                ->has(
                    'genre',
                    fn(Assert $page) => $page
                        ->where('id', $this->genre->id)
                        ->where('name', $this->genre->name)
                        ->where('avg_rating', 3)
                        ->etc()
                )
        );
    }

    public function test_genres_shows_the_correct_games_count(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres/' . $this->genre->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Genres/Show')
                ->has(
                    'genre',
                    fn(Assert $page) => $page
                        ->where('id', $this->genre->id)
                        ->where('name', $this->genre->name)
                        ->where('games_count', 3)
                        ->etc()
                )
        );
    }
}
