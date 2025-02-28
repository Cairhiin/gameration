<?php

namespace Tests\Feature\Actions\Games\Reviews;

use Tests\TestCase;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use App\Models\Game;

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

    public function test_games_reviews_edit_page_returns_a_method_not_allowed_responsed(): void
    {
        $response = $this->actingAs($this->user, 'web')->get("/games/{$this->game->id}/reviews/edit");

        $response->assertStatus(200);
    }
}
