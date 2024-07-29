<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Developer;
use App\Models\Publisher;
use App\Policies\GamePolicy;
use App\Policies\GenrePolicy;
use App\Policies\DeveloperPolicy;
use App\Policies\PublisherPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Game::class => GamePolicy::class,
        Developer::class => DeveloperPolicy::class,
        Publisher::class => PublisherPolicy::class,
        Genre::class => GenrePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
