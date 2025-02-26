<?php

namespace Tests\Feature\Actions\Developers;

use Tests\TestCase;
use App\Models\Role;
use App\Enums\RoleName;
use App\Models\Developer;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class DeleteTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Developer $developer;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->developer = Developer::create([
            'name' => "test",
            'country' => "Finland",
            'year' => 2005,
            'city' =>  "Helsinki",
            'user_id' => $this->user->id
        ]);
    }

    public function test_developers_delete_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->delete('/developers/' . $this->developer->id);

        $response->assertStatus(403);
    }

    public function test_developers_delete_route_successfully_deletes_a_developer_when_user_is_authorized(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->delete('/developers/' . $this->developer->id);

        $response->assertRedirectToRoute('developers.index')->assertSessionHas('message', 'Developer' . SystemMessage::DELETE_SUCCESS);
    }
}
