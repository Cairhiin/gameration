<?php

namespace Tests\Feature\Actions\Games;

use Tests\TestCase;
use App\Models\Game;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class IndexTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_games_index_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/games');

        $response->assertStatus(200);
    }

    public function test_games_index_page_returns_an_inertia_view_with_paginated_games(): void
    {
        $this->createGame();

        $response = $this->actingAs($this->user, 'web')->get('/games');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/Index')
                ->has(
                    'games',
                    fn(Assert $page) => $page
                        ->has(
                            'data.0',
                            fn(Assert $page) => $page
                                ->has('id')
                                ->has('name')
                                ->has('description')
                                ->has('developer_id')
                                ->has('publisher_id')
                                ->has('released_at')
                                ->has('user_id')
                                ->has('avg_rating')
                                ->has('genres')
                                ->has('median_rating')
                                ->has('rating_count')
                                ->has('image')
                                ->etc()
                        )
                        ->has('first_page_url')
                        ->etc()
                )
        );
    }

    public function test_games_index_page_contains_the_games(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/games');

        $response->assertViewMissing('No games found');
    }
}
