<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Enums\RoleName;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdminRole();
        $this->createModeratorRole();
        $this->createUserRole();
    }

    protected function createRole(RoleName $role, Collection $permissions): void
    {
        $newRole = Role::create(['name' => $role->value]);
        $newRole->permissions()->sync($permissions);
    }

    protected function createAdminRole(): void
    {
        $permissions = Permission::query()
            ->where('name', 'like', 'user:%')
            ->orWhere('name', 'like', 'developer:%')
            ->orWhere('name', 'like', 'game:%')
            ->orWhere('name', 'like', 'publisher:%')
            ->orWhere('name', 'like', 'genre:%')
            ->orWhere('name', 'like', 'comment:%')
            ->orWhere('name', 'like', 'review:%')
            ->pluck('id');

        $this->createRole(RoleName::ADMIN, $permissions);
    }

    protected function createModeratorRole(): void
    {
        $permissions = Permission::query()
            ->where('name', 'like', '%:viewAny')
            ->orWhere('name', 'like', '%:view')
            ->orWhere('name', 'like', '%:update')
            ->orWhere('name', 'like', '%:create')
            ->pluck('id');

        $this->createRole(RoleName::MODERATOR, $permissions);
    }

    protected function createUserRole(): void
    {
        $permissions = Permission::query()
            ->where('name', 'like', '%:viewAny')
            ->orWhere('name', 'like', '%:view')
            ->orWhere('name', 'like', '%:create')
            ->pluck('id');

        $this->createRole(RoleName::USER, $permissions);
    }
}
