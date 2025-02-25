<?php

namespace Tests\Feature\Actions\Genres;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Genre;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasSeededDatabase;

class DeleteTest extends TestCase
{
    use HasSeededDatabase;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_genres_delete_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $genre = Genre::create([
            'name' => "test",
        ]);

        $response = $this->actingAs($this->user, 'web')->delete('/genres/' . $genre->id);

        $response->assertForbidden();
    }

    public function test_genres_delete_route_successfully_deletes_a_genre_when_user_is_authorized(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $genre = Genre::create([
            'name' => "test",
        ]);

        $response = $this->actingAs($this->user, 'web')->delete('/genres/' . $genre->id);

        $response->assertRedirectToRoute('genres.index')->assertSessionHas('message', 'The genre has been deleted!');
    }
}
