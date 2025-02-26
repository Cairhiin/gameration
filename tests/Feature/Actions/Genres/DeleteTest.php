<?php

namespace Tests\Feature\Actions\Genres;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Genre;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class DeleteTest extends TestCase
{
    use HasRolesAndPermissions;
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

    public function test_genres_delete_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->delete('/genres/' . $this->genre->id);

        $response->assertForbidden();
    }

    public function test_genres_delete_route_successfully_deletes_a_genre_when_user_is_authorized(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->delete('/genres/' . $this->genre->id);

        $response->assertRedirectToRoute('genres.index')->assertSessionHas('message', 'Genre' . SystemMessage::DELETE_SUCCESS);
    }
}
