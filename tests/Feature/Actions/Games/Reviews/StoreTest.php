<?php

namespace Tests\Feature\Actions\Games\Reviews;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class StoreTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Game $game;
    private array $review;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->game = $this->createGame($this->user);

        $this->review = [
            'game_id' => $this->game->id,
            'user_id' => $this->user->id,
            'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        ];
    }

    public function test_games_reviews_store_route_should_not_return_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->post("/games/{$this->game->id}/reviews", $this->review);

        $response->assertFound();
    }

    public function test_games_reviews_store_route_should_fail_validation_when_content_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->review['content'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', "/games/{$this->game->id}/reviews", $this->review);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_games_reviews_store_route_should_fail_validation_when_content_is_too_short(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->review['content'] = 'Lorem ipsum';

        $response = $this->actingAs($this->user)
            ->json('POST', "/games/{$this->game->id}/reviews", $this->review);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_games_reviews_store_route_should_store_a_review_as_a_moderator(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('POST', "/games/{$this->game->id}/reviews", $this->review);

        $response->assertRedirectToRoute('games.show', ['game' => $this->game->id])->assertSessionHas('message', 'Review' . SystemMessage::STORE_SUCCESS);

        $this->assertDatabaseHas('game_user', [
            'game_id' => $this->game->id,
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        ]);
    }

    public function test_games_reviews_store_route_should_store_a_review_as_a_normal_user(): void
    {
        $response = $this->actingAs($this->user)
            ->json('POST', "/games/{$this->game->id}/reviews", $this->review);

        $response->assertRedirectToRoute('games.show', ['game' => $this->game->id])->assertSessionHas('message', 'Review' . SystemMessage::AWAIT_APPROVAL);

        $this->assertDatabaseHas('game_user', [
            'game_id' => $this->game->id,
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        ]);
    }
}
