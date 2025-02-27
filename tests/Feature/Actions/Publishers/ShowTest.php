<?php

namespace Tests\Feature\Actions\Publishers;

use Tests\TestCase;
use App\Models\Publisher;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;

class ShowTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Publisher $publisher;
    private Collection $games;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->publisher = $this->createPublisher();

        $this->games = $this->createGames(3);

        foreach ($this->games as $index => $game) {
            $game->publisher_id = $this->publisher->id;
            $game = $this->rateGame($game, $index + 1);
            $game->save();
        }
    }

    public function test_publishers_show_page_returns_a_successful_response(): void
    {

        $response = $this->actingAs($this->user, 'web')->get('/publishers/' . $this->publisher->id);

        $response->assertStatus(200);
    }

    public function test_publishers_show_page_returns_an_inertia_view(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/publishers/' . $this->publisher->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Publishers/Show')
                ->has(
                    'publisher',
                    fn(Assert $page) => $page
                        ->where('id', $this->publisher->id)
                        ->where('name', $this->publisher->name)
                        ->where('city', $this->publisher->city)
                        ->where('country', $this->publisher->country)
                        ->where('year', $this->publisher->year)
                        ->etc()
                )
        );
    }

    public function test_publishers_show_page_returns_a_paginated_list_of_games(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/publishers/' . $this->publisher->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Publishers/Show')
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

    public function test_publishers_show_page_shows_the_correct_average_rating(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/publishers/' . $this->publisher->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Publishers/Show')
                ->has(
                    'publisher',
                    fn(Assert $page) => $page
                        ->where('id', $this->publisher->id)
                        ->where('name', $this->publisher->name)
                        ->where('avg_rating', 3)
                        ->etc()
                )
        );
    }

    public function test_publishers_show_page_shows_the_correct_games_count(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/publishers/' . $this->publisher->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Publishers/Show')
                ->has(
                    'publisher',
                    fn(Assert $page) => $page
                        ->where('id', $this->publisher->id)
                        ->where('name', $this->publisher->name)
                        ->where('games_count', 3)
                        ->etc()
                )
        );
    }
}
