<?php

namespace Tests\Feature\Actions\Games;

use Tests\TestCase;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Models\Game;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class DeleteTest extends TestCase
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

    public function test_games_delete_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->delete('/games/' . $this->game->id);

        $response->assertForbidden();
    }

    public function test_games_delete_route_successfully_deletes_a_game_when_user_is_authorized(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->delete('/games/' . $this->game->id);

        $response->assertRedirectToRoute('games.index')->assertSessionHas('message', 'Game' . SystemMessage::DELETE_SUCCESS);
    }
}
