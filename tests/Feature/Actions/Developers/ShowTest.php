<?php

namespace Tests\Feature\Actions\Developers;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Developer;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;

class ShowTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Developer $developer;
    private Collection $games;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->developer = Developer::create([
            'name' => "test",
            'country' => "Finland",
            'year' => 2005,
            'city' =>  "Helsinki",
            'user_id' => $this->user->id
        ]);

        $this->games = $this->createGames(3);

        foreach ($this->games as $index => $game) {
            $game->developer_id = $this->developer->id;
            $this->rateGame($game, $index + 1);
        }
    }

    public function test_developers_show_page_returns_a_successful_response(): void
    {

        $response = $this->actingAs($this->user, 'web')->get('/developers/' . $this->developer->id);

        $response->assertStatus(200);
    }

    public function test_developers_show_page_returns_an_inertia_view(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/developers/' . $this->developer->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Developers/Show')
                ->has(
                    'developer',
                    fn(Assert $page) => $page
                        ->where('id', $this->developer->id)
                        ->where('name', $this->developer->name)
                        ->where('city', $this->developer->city)
                        ->where('country', $this->developer->country)
                        ->where('year', $this->developer->year)
                        ->etc()
                )
        );
    }

    public function test_developers_show_page_returns_a_paginated_list_of_games(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/developers/' . $this->developer->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Developers/Show')
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

    public function test_developers_show_page_shows_the_correct_average_rating(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/developers/' . $this->developer->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Developers/Show')
                ->has(
                    'developer',
                    fn(Assert $page) => $page
                        ->where('id', $this->developer->id)
                        ->where('name', $this->developer->name)
                        ->where('avg_rating', 3)
                        ->etc()
                )
        );
    }

    public function test_developers_show_page_shows_the_correct_games_count(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/developers/' . $this->developer->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Developers/Show')
                ->has(
                    'developer',
                    fn(Assert $page) => $page
                        ->where('id', $this->developer->id)
                        ->where('name', $this->developer->name)
                        ->where('games_count', 3)
                        ->etc()
                )
        );
    }
}
