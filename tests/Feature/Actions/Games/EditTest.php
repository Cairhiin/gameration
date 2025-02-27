<?php

namespace Tests\Feature\Actions\Games;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Role;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class EditTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Game $game;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->game = $this->createGame();
    }

    public function test_games_edit_page_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/games/' . $this->game->id . '/edit');

        $response->assertStatus(403);
    }

    public function test_games_edit_page_returns_a_successful_response_when_user_is_a_moderator(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::MODERATOR)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/games/' . $this->game->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_games_edit_page_returns_a_successful_response_when_user_is_an_admin(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/games/' . $this->game->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_games_edit_page_returns_an_inertia_view_with_the_game_when_authorized(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/games/' . $this->game->id . '/edit');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/Edit')
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
        );
    }
}
