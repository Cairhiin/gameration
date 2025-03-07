<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_permission_model_can_create_a_permission(): void
    {
        Permission::create(['name' => 'create:game']);

        $this->assertDatabaseHas('permissions', ['name' => 'create:game']);
    }

    public function test_permission_model_can_retrieve_a_permission(): void
    {
        Permission::create(['name' => 'create:game']);

        $permission = Permission::first();

        $this->assertEquals($permission->name, 'create:game');
    }

    public function test_permission_model_has_roles_relationship(): void
    {
        $permission = Permission::create(['name' => 'create:game']);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $permission->roles());
    }
}
