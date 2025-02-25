<?php

namespace Tests\Feature\Actions\Genres;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Genre;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasSeededDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class EditTest extends TestCase
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

    public function test_genres_edit_page_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres/' . $this->genre->id . '/edit');

        $response->assertForbidden();
    }

    public function test_genres_edit_page_returns_a_successful_response_when_user_is_a_moderator(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::MODERATOR)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/genres/' . $this->genre->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_genres_edit_page_returns_a_successful_response_when_user_is_an_admin(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/genres/' . $this->genre->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_genres_edit_page_returns_an_inertia_view_with_the_genre_when_authorized(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/genres/' . $this->genre->id . '/edit');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Genres/Edit')
                ->has(
                    'genre',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('games_count')
                        ->has('avg_rating')
                        ->has('created_at')
                        ->has('updated_at')
                )
        );
    }
}
