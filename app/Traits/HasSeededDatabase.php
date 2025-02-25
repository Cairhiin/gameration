<?php

namespace App\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait HasSeededDatabase
{
    use RefreshDatabase;

    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate');

        $this->artisan('db:seed');

        $this->app[Kernel::class]->setArtisan(null);
    }

    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:fresh', $this->shouldDropViews() ? [
                '--drop-views' => true,
            ] : []);

            $this->artisan('db:seed');

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}
