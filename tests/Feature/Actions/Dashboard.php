<?php

namespace Tests\Feature\Actions;

use Tests\TestCase;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class Dashboard extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_dashboard_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_dashboard_page_returns_an_inertia_view(): void
    {

        $friends = $this->createUsers(3);

        foreach ($friends as $key => $friend) {
            $this->user->friendsOfMine()->attach($friend->id, ['accepted' => $key % 2 === 0 ? true : false]);
        }

        $games = $this->createGames(3);

        foreach ($games as $key => $game) {
            $game->genres()->attach($this->createGenre()->id);
            $this->user->games()->attach($game->id, ['rating' => $key]);
        }

        $response = $this->actingAs($this->user, 'web')->get('/dashboard');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Dashboard/Show')
                ->has('user')
                ->has('friends')
                ->has(
                    'friends.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('username')
                        ->etc()
                )
                ->has('latestRatedGames')
                ->has(
                    'latestRatedGames.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->etc()
                        ->has('game', fn(Assert $page) => $page
                            ->has('name')
                            ->etc())
                )
                ->has('highestRatedGames')
                ->has(
                    'highestRatedGames.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->etc()
                        ->has('game', fn(Assert $page) => $page
                            ->has('name')
                            ->etc())
                )
                ->has('favoriteGenres')
                ->has(
                    'dashboardData',
                    fn(Assert $page) => $page
                        ->has('totalReviews')
                        ->has('totalFriends')
                        ->has('totalRatings')
                        ->has('averageRating')
                )
        );
    }

    public function test_dashboard_page_returns_the_right_number_of_total_reviews(): void
    {
        $games = $this->createGames(3);

        foreach ($games as $key => $game) {
            $game->genres()->attach($this->createGenre()->id);
            $this->user->games()->attach($game->id, ['content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.']);
        }

        /* Add some ratings to the user - these should not be counted */
        $ratedGames = $this->createGames(3);

        foreach ($ratedGames as $key => $game) {
            $game->genres()->attach($this->createGenre()->id);
            $this->user->games()->attach($game->id, ['rating' => $key + 1]);
        }

        $response = $this->actingAs($this->user, 'web')->get('/dashboard');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->has('dashboardData', fn(Assert $page) => $page
                    ->where('totalReviews', 3)
                    ->etc())
                ->etc()
        );
    }

    public function test_dashboard_page_returns_the_right_number_of_total_ratings(): void
    {
        $games = $this->createGames(3);

        foreach ($games as $key => $game) {
            $game->genres()->attach($this->createGenre()->id);
            $this->user->games()->attach($game->id, ['rating' => $key + 1]);
        }

        /* Add some reviews to the user - these should not be counted */
        $reviewedGames = $this->createGames(3);

        foreach ($reviewedGames as $key => $game) {
            $game->genres()->attach($this->createGenre()->id);
            $this->user->games()->attach($game->id, ['content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.']);
        }

        $response = $this->actingAs($this->user, 'web')->get('/dashboard');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->has('dashboardData', fn(Assert $page) => $page
                    ->where('totalRatings', 3)
                    ->etc())
                ->etc()
        );
    }

    public function test_dashboard_page_returns_the_right_number_of_total_friends(): void
    {
        $friends = $this->createUsers(3);

        foreach ($friends as $key => $friend) {
            $this->user->friendsOfMine()->attach($friend->id, ['accepted' => true]);
        }

        /* These are potential friends - these should not be counted */
        $potentialFriends = $this->createUsers(3);

        foreach ($potentialFriends as $key => $friend) {
            $this->user->friendsOfMine()->attach($friend->id, ['accepted' => false]);
        }

        /* Add a new user and befriend the logged in user */
        $user = $this->createUser();
        $user->friendsOfMine()->attach($this->user->id, ['accepted' => true]);

        $response = $this->actingAs($this->user, 'web')->get('/dashboard');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->has('dashboardData', fn(Assert $page) => $page
                    ->where('totalFriends', 4)
                    ->etc())
                ->etc()
        );
    }

    public function test_dashboard_page_returns_the_right_average_rating(): void
    {
        $games = $this->createGames(3);

        foreach ($games as $key => $game) {
            $game->genres()->attach($this->createGenre()->id);
            $this->user->games()->attach($game->id, ['rating' => $key + 1]);
        }

        /* Add some reviews to the user - these should not be counted */
        $reviewedGames = $this->createGames(3);

        foreach ($reviewedGames as $key => $game) {
            $game->genres()->attach($this->createGenre()->id);
            $this->user->games()->attach($game->id, ['content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.']);
        }

        $response = $this->actingAs($this->user, 'web')->get('/dashboard');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->has('dashboardData', fn(Assert $page) => $page
                    ->where('averageRating', 2)
                    ->etc())
                ->etc()
        );
    }
}
