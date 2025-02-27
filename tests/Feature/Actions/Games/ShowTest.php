<?php

namespace Tests\Feature\Actions\Games;

use Tests\TestCase;
use App\Models\Game;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;

class ShowTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Game $game;
    private Collection $users;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->game = $this->createGame();
    }

    public function test_games_show_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/games/' . $this->game->id);

        $response->assertStatus(200);
    }

    public function test_games_show_page_returns_an_inertia_view(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/games/' . $this->game->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/Show')
                ->has(
                    'game',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('description')
                        ->has('developer_id')
                        ->has('publisher_id')
                        ->has('released_at')
                        ->has('user_id')
                        ->has('avg_rating')
                        ->has('created_at')
                        ->has('updated_at')
                        ->has('genres')
                        ->has('median_rating')
                        ->has('rating_count')
                        ->has('image')
                        ->etc()
                )
                ->has('last_user_ratings')
                ->has('rating')
                ->has('user_review')
                ->has('reviews')
        );
    }

    public function test_games_show_page_shows_the_correct_average_rating(): void
    {
        $users = $this->createUsers(3);

        foreach ($users as $index => $user) {
            $this->rateGame($this->game, $index * 2, $user);
        }

        $response = $this->actingAs($this->user, 'web')->get('/games/' . $this->game->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/Show')
                ->has(
                    'game',
                    fn(Assert $page) => $page
                        ->where('name', $this->game->name)
                        ->where('avg_rating', 3)
                        ->etc()
                )
        );
    }

    public function test_games_show_page_shows_the_correct_ratings_count(): void
    {
        $users = $this->createUsers(3);

        foreach ($users as $index => $user) {
            $this->rateGame($this->game, $index * 2, $user);
        }

        $response = $this->actingAs($this->user, 'web')->get('/games/' . $this->game->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/Show')
                ->has(
                    'game',
                    fn(Assert $page) => $page
                        ->where('name', $this->game->name)
                        ->etc()
                )
                ->has('last_user_ratings', 2)
                ->etc()
        );
    }
}
