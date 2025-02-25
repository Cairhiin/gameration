<?php

namespace Tests\Feature\Actions\Genres;

use Tests\TestCase;
use App\Models\Role;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasSeededDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class CreateTest extends TestCase
{
    use HasSeededDatabase;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_genres_create_page_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres/create');

        $response->assertForbidden();
    }

    public function test_genres_create_page_returns_a_successful_response_when_user_is_a_moderator(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::MODERATOR)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/genres/create');

        $response->assertStatus(200);
    }

    public function test_genres_create_page_returns_a_successful_response_when_user_is_an_admin(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/genres/create');

        $response->assertStatus(200);
    }

    public function test_genres_create_page_returns_an_inertia_view_when_authorized(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/genres/create');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Genres/Create')
        );
    }
}
