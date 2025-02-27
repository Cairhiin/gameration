<?php

namespace Tests\Feature\Actions\Games;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Role;
use App\Models\Genre;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class UpdateTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Game $game;
    private array $gameData;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->game = $this->createGame();
        $this->gameData = [
            'name' => "test",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
            'released' => date('Y-m-d H:i:s'),
            'developer' => $this->createDeveloper(),
            'publisher' => $this->createPublisher(),
            'genres' => $this->createGenres(2),
            'user_id' => $this->user->id
        ];
    }

    public function test_games_update_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->put('/games/' . $this->game->id, $this->gameData);

        $response->assertForbidden();
    }

    public function test_games_update_route_should_fail_validation_when_developer_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['developer'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['developer']);
    }

    public function test_games_update_route_should_fail_validation_when_publisher_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['publisher'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['publisher']);
    }

    public function test_games_update_route_should_fail_validation_when_genres_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['genres'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['genres']);
    }

    public function test_games_update_route_should_fail_validation_when_released_at_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['released'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['released']);
    }

    public function test_games_update_route_should_fail_validation_when_released_at_is_not_a_date(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['released'] = 1234;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['released']);
    }

    public function test_games_update_route_should_fail_validation_when_released_at_is_in_the_future(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['released'] = date('Y-m-d', strtotime('+1 day'));

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['released']);
    }

    public function test_games_update_route_should_fail_validation_when_name_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['name'] = 1;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_games_update_route_should_fail_validation_when_name_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['name'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_games_update_route_should_fail_validation_when_name_is_too_short(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['name'] = "te";

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_games_update_route_should_fail_validation_when_description_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['description'] = 1;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_games_update_route_should_fail_validation_when_description_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['description'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_games_update_route_should_fail_validation_when_description_is_too_short(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->gameData['description'] = "test";

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_games_update_route_should_update_a_game_successfully()
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $game = Game::first();

        $response->assertRedirectToRoute('games.show', $game->id)->assertSessionHas('message', 'Game' . SystemMessage::UPDATE_SUCCESS);

        $this->assertDatabaseHas('games', [
            'name' => "test",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
        ]);
    }

    public function test_games_update_route_should_update_the_game_genres_successfully()
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('PUT', '/games/' . $this->game->id, $this->gameData);

        $game = Game::first();
        $genres = Genre::all();

        $this->assertDatabaseHas('game_genre', [
            'game_id' => $game->id,
            'genre_id' => $genres->random()->id
        ]);
    }
}
