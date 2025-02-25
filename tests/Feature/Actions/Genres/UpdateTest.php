<?php

namespace Tests\Feature\Actions\Genres;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Genre;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasSeededDatabase;

class UpdateTest extends TestCase
{
    use HasSeededDatabase;
    use HasTestFunctions;

    private Genre $genre;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->genre = Genre::create([
            'name' => "test",
        ]);
    }

    public function test_genres_update_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->put('/genres/' . $this->genre->id, $this->genre->toArray());

        $response->assertForbidden();
    }

    public function test_genres_update_route_should_fail_validation_when_name_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('PUT', '/genres/' . $this->genre->id, ['name' => 1]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_genres_update_route_should_fail_validation_when_name_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('PUT', '/genres/' . $this->genre->id, ['name' => null]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_genres_update_route_should_fail_validation_when_name_is_too_short(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('PUT', '/genres/' . $this->genre->id, ['name' => "te"]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_genres_update_route_should_store_a_genre_successfully()
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('PUT', '/genres/' . $this->genre->id, ["name" => "test1"]);

        $genre = Genre::where('name', "test1")->first();

        $response->assertRedirectToRoute('genres.show', $genre->id)->assertSessionHas('message', 'Genre updated successfully!');

        $this->assertDatabaseHas('genres', [
            'name' => "test1"
        ]);
    }
}
