<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Enums\RoleName;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()
            ->create();

        $this->user->roles()->sync(Role::where('name', RoleName::USER->value)->first());

        foreach (Permission::pluck('name') as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }

    public function test_genre_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres');

        $response->assertStatus(200);
    }

    public function test_genre_page_returns_a_view(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Genres/Index')
                ->has('genres')
        );
    }

    public function test_genre_page_contains_the_genres(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres');

        $response->assertViewMissing('No genres found');
    }
}
