<?php

namespace Tests\Feature\Actions\Persons;

use Tests\TestCase;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class CreateTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_persons_create_page_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/persons/create');

        $response->assertForbidden();
    }

    public function test_persons_create_page_returns_a_successful_response_when_user_is_a_moderator(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->get('/persons/create');

        $response->assertStatus(200);
    }

    public function test_persons_create_page_returns_a_successful_response_when_user_is_an_admin(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->get('/persons/create');

        $response->assertStatus(200);
    }

    public function test_persons_create_page_returns_an_inertia_view_when_authorized(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->get('/persons/create');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Persons/Create')
        );
    }
}
