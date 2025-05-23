<?php

namespace Tests\Feature\Actions\Games;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class UpdateUserRatingTest extends TestCase
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

    public function test_games_update_route_should_update_rating_successfully()
    {
        $this->user->assignRole(RoleName::USER);
        $this->user->games()->attach($this->game->id, ['user_id' => $this->user->id, 'rating' => 3]);

        $response = $this->actingAs($this->user)
            ->json('POST', '/games/' . $this->game->id . '/rate', ['rating' => 5, 'game_id' => $this->game->id]);

        $game = Game::first();

        $response->assertRedirectToRoute('games.show', $game->id)->assertSessionHas('message', 'Rating' . SystemMessage::UPDATE_SUCCESS);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $this->user->id,
            'game_id' => $this->game->id,
            'rating' => 5
        ]);
    }
}
