<?php

namespace Tests\Feature\Actions\Publishers;

use Tests\TestCase;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Models\Publisher;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class DeleteTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Publisher $publisher;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->publisher = Publisher::create([
            'name' => "test",
            'country' => "Finland",
            'year' => 2005,
            'city' =>  "Helsinki",
            'user_id' => $this->user->id
        ]);
    }

    public function test_publishers_delete_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->delete('/publishers/' . $this->publisher->id);

        $response->assertForbidden();
    }

    public function test_publishers_delete_route_successfully_deletes_a_publisher_when_user_is_authorized(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->delete('/publishers/' . $this->publisher->id);

        $response->assertRedirectToRoute('publishers.index')->assertSessionHas('message', 'Publisher' . SystemMessage::DELETE_SUCCESS);
    }
}
