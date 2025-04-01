<?php

namespace Tests\Feature\Actions\Games;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use Illuminate\Http\UploadedFile;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Support\Facades\Storage;

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
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('image.jpg');

        $game = [
            'name' => "test game",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            'image' => $file,
            'genres' => $this->createGenres(2),
            'user_id' => $this->user->id,
            'publisher' => $this->createPublisher(),
            'developer' => $this->createDeveloper(),
            'released' => date('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', '/games', $game);
        $this->assertDatabaseHas('games', ['name' => "test game"]);

        $this->game = Game::where('name', 'test game')->first();

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertExists($this->game->image);

        $response = $this->actingAs($this->user, 'web')->delete('/games/' . $this->game->id);

        $response->assertRedirectToRoute('games.index')->assertSessionHas('message', 'Game' . SystemMessage::DELETE_SUCCESS);
        $this->assertDatabaseMissing('games', ['id' => $this->game->id, 'name' => "test game"]);

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertMissing($this->game->image);
    }
}
