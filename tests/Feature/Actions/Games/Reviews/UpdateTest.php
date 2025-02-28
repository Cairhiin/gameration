<?php

namespace Tests\Feature\Actions\Games\Reviews;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Models\GameUser as Review;
use App\Traits\HasRolesAndPermissions;

class UpdateTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Game $game;
    private Review $review;
    private array $updatedReview;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->game = $this->createGame($this->user);

        $this->review = $this->createReview($this->game, $this->user);

        $this->updatedReview = [
            'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        ];
    }

    public function test_games_reviews_update_route_should_not_return_a_forbidden_response_when_user_has_no_moderation_permission_but_is_author(): void
    {
        $response = $this->actingAs($this->user, 'web')->put("/games/{$this->game->id}/reviews/{$this->review->id}", $this->updatedReview);

        $response->assertFound();
    }

    public function test_games_reviews_update_route_should_return_a_forbidden_response_when_user_has_no_moderation_permission_and_is_not_the_author(): void
    {
        $randomUser = $this->createUser();

        $response = $this->actingAs($randomUser, 'web')->put("/games/{$this->game->id}/reviews/{$this->review->id}", $this->updatedReview);

        $response->assertForbidden();
    }

    public function test_games_reviews_update_route_should_not_return_a_forbidden_response_when_user_has_moderation_permission_and_is_not_the_author(): void
    {
        $randomUser = $this->createUser();
        $randomUser->roles()->sync(Role::where('name', RoleName::MODERATOR)->first()->id);

        $response = $this->actingAs($randomUser, 'web')->put("/games/{$this->game->id}/reviews/{$this->review->id}", $this->updatedReview);

        $response->assertFound();
    }

    public function test_games_reviews_update_route_should_fail_validation_when_content_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->updatedReview['content'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', "/games/{$this->game->id}/reviews/{$this->review->id}", $this->updatedReview);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_games_reviews_update_route_should_fail_validation_when_content_is_too_short(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->updatedReview['content'] = 'Lorem ipsum';

        $response = $this->actingAs($this->user)
            ->json('PUT', "/games/{$this->game->id}/reviews/{$this->review->id}", $this->updatedReview);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_games_reviews_update_route_should_update_a_review_as_a_moderator(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('PUT', "/games/{$this->game->id}/reviews/{$this->review->id}", $this->updatedReview);

        $response->assertRedirectToRoute('games.show', ['game' => $this->game->id])->assertSessionHas('message', 'Review' . SystemMessage::UPDATE_SUCCESS);

        $this->assertDatabaseHas('game_user', [
            'game_id' => $this->game->id,
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        ]);
    }

    public function test_games_reviews_update_route_should_update_a_review_as_a_normal_user(): void
    {
        $response = $this->actingAs($this->user)
            ->json('PUT', "/games/{$this->game->id}/reviews/{$this->review->id}", $this->updatedReview);

        $response->assertRedirectToRoute('games.show', ['game' => $this->game->id])->assertSessionHas('message', 'Review' . SystemMessage::AWAIT_APPROVAL);

        $this->assertDatabaseHas('game_user', [
            'game_id' => $this->game->id,
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        ]);
    }
}
