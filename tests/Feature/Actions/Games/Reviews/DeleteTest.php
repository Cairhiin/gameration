<?php

namespace Tests\Feature\Actions\Games\Reviews;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Models\GameUser as Review;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Collection;

class DeleteTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Game $game;
    private Review $review;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->game = $this->createGame($this->user);
        $this->review = $this->createReview($this->game, $this->user);
    }

    public function test_games_reviews_delete_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $randomUser = $this->createUser();

        /* Random user can't delete review from another user */
        $response = $this->actingAs($randomUser, 'web')->delete("/games/{$this->game->id}/reviews/{$this->review->id}");

        $response->assertForbidden();
    }

    public function test_games_reviews_delete_route_successfully_deletes_a_review_when_user_is_authorized(): void
    {
        $randomUser = $this->createUser();

        $randomUser->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->delete("/games/{$this->game->id}/reviews/{$this->review->id}");

        $response->assertRedirectToRoute('games.show', ['game' => $this->game->id])->assertSessionHas('message', 'Review' . SystemMessage::DELETE_SUCCESS);

        $this->assertDatabaseMissing('game_user', ['content' => $this->review->content]);
    }

    public function test_games_reviews_delete_route_successfully_deletes_a_review_when_user_is_the_author(): void
    {
        $response = $this->actingAs($this->user, 'web')->delete("/games/{$this->game->id}/reviews/{$this->review->id}");

        $response->assertRedirectToRoute('games.show', ['game' => $this->game->id])->assertSessionHas('message', 'Review' . SystemMessage::DELETE_SUCCESS);

        $this->assertDatabaseMissing('game_user', ['content' => $this->review->content]);
    }

    public function test_games_reviews_delete_route_doesnt_delete_a_rating_when_the_user_deletes_a_review(): void
    {
        $response = $this->actingAs($this->user, 'web')->delete("/games/{$this->game->id}/reviews/{$this->review->id}");

        $response->assertRedirectToRoute('games.show', ['game' => $this->game->id])->assertSessionHas('message', 'Review' . SystemMessage::DELETE_SUCCESS);

        $this->assertDatabaseHas('game_user', [
            'game_id' => $this->game->id,
            'user_id' => $this->user->id,
            'content' => null,
        ]);
    }

    public function test_games_reviews_delete_route_deletes_the_entire_game_user_when_the_user_deletes_a_review_without_a_rating(): void
    {
        $this->review->update(['rating' => 0]);

        $response = $this->actingAs($this->user, 'web')->delete("/games/{$this->game->id}/reviews/{$this->review->id}");

        $response->assertRedirectToRoute('games.show', ['game' => $this->game->id])->assertSessionHas('message', 'Review' . SystemMessage::DELETE_SUCCESS);

        $this->assertDatabaseMissing('game_user', ['game_id' => $this->game->id, 'user_id' => $this->user->id]);
    }
}
