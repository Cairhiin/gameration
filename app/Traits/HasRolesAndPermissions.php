<?php

namespace App\Traits;

use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait HasRolesAndPermissions
{
    use RefreshDatabase;

    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate');

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->app[Kernel::class]->setArtisan(null);
    }

    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:fresh', $this->shouldDropViews() ? [
                '--drop-views' => true,
            ] : []);

            $this->seed(PermissionSeeder::class);
            $this->seed(RoleSeeder::class);

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}
