<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = [
            'viewAny',
            'view',
            'create',
            'update',
            'delete',
            'restore',
            'forceDelete',
        ];

        $resources = [
            'user',
            'developer',
            'game',
            'book',
            'publisher',
            'genre',
            'comment',
            'review',
            'achievement',
            'person'
        ];

        collect($resources)
            ->crossJoin($actions)
            ->map(function ($set) {
                return implode(':', $set);
            })->each(function ($permission) {
                Permission::create(['name' => $permission]);
            });
    }
}
