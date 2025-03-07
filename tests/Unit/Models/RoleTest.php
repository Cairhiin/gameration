<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_model_can_create_a_role(): void
    {
        Role::factory(['name' => 'admin'])->create();

        $this->assertDatabaseHas('roles', ['name' => 'admin']);
    }

    public function test_role_model_can_retrieve_a_role(): void
    {
        Role::factory(['name' => 'admin'])->create();

        $role = Role::first();

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('admin', $role->name);
    }

    public function test_role_model_has_users_relationship(): void
    {
        $role = Role::factory(['name' => 'admin'])->create();

        $this->assertInstanceOf(BelongsToMany::class, $role->users());
    }

    public function test_role_model_has_permissions_relationship(): void
    {
        $role = Role::factory(['name' => 'admin'])->create();

        $this->assertInstanceOf(BelongsToMany::class, $role->permissions());
    }
}
