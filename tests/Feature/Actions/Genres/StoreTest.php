<?php

namespace Tests\Feature\Actions\Genres;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Genre;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class StoreTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private array $genre;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->genre = [
            'name' => "test",
        ];
    }

    public function test_genres_store_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->post('/genres', $this->genre);

        $response->assertForbidden();
    }

    public function test_genres_store_route_should_fail_validation_when_name_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->genre['name'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/genres', $this->genre);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_genres_store_route_should_fail_validation_when_name_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->genre['name'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/genres', $this->genre);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_genres_store_route_should_fail_validation_when_name_is_too_short(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->genre['name'] = "te";

        $response = $this->actingAs($this->user)
            ->json('POST', '/genres', $this->genre);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_genres_store_route_should_store_a_genre_successfully()
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('POST', '/genres', $this->genre);

        $genre = Genre::first();

        $response->assertRedirectToRoute('genres.show', $genre->id)->assertSessionHas('message', 'Genre' . SystemMessage::STORE_SUCCESS);

        $this->assertDatabaseHas('genres', [
            'name' => "test"
        ]);
    }
}
