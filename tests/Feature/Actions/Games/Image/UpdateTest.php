<?php

namespace Tests\Feature\Actions\Games\Image;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use Illuminate\Http\UploadedFile;
use App\Traits\HasRolesAndPermissions;

class UpdateTest extends TestCase
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

    public function test_image_update_route_should_fail_validation_when_image_is_too_big(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::MODERATOR)->first()->id);

        $gameData = ['image' => UploadedFile::fake()->image('image.jpg')->size(2 * 1024 * 1024 + 1)];

        $response = $this->actingAs($this->user)->json('PUT', '/games/' . $this->game->id . '/image', $gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_image_update_route_should_fail_validation_when_image_is_wrong_format(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::MODERATOR)->first()->id);

        $gameData = ['image' => UploadedFile::fake()->create('document.pdf')];

        $response = $this->actingAs($this->user)->json('PUT', '/games/' . $this->game->id . '/image', $gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_image_update_route_should_upload_an_image_correctly(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::MODERATOR)->first()->id);

        $gameData = ['image' => UploadedFile::fake()->image('image.jpg')];

        $response = $this->actingAs($this->user)->json('PUT', '/games/' . $this->game->id . '/image', $gameData);

        $game = Game::first();

        $response->assertRedirectToRoute('games.show', $game->id)->assertSessionHas('message', 'Image' . SystemMessage::UPDATE_SUCCESS);

        $this->assertDatabaseHas('games', [
            'image' => $game->image
        ]);
    }
}
