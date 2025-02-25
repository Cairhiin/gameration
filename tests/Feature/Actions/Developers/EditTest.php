<?php

namespace Tests\Feature\Actions\Developers;

use Tests\TestCase;
use App\Models\Role;
use App\Enums\RoleName;
use App\Models\Developer;
use App\Traits\HasTestFunctions;
use App\Traits\HasSeededDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class EditTest extends TestCase
{
    use HasSeededDatabase;
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

    public function test_developers_edit_page_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/developers/' . $this->developer->id . '/edit');

        $response->assertStatus(403);
    }

    public function test_developers_edit_page_returns_a_successful_response_when_user_is_a_moderator(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::MODERATOR)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/developers/' . $this->developer->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_developers_edit_page_returns_a_successful_response_when_user_is_an_admin(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/developers/' . $this->developer->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_developers_edit_page_returns_an_inertia_view_with_the_developer_when_authorized(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/developers/' . $this->developer->id . '/edit');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Developers/Edit')
                ->has(
                    'developer',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('city')
                        ->has('country')
                        ->has('year')
                        ->has('user_id')
                        ->has('games_count')
                        ->has('avg_rating')
                        ->has('created_at')
                        ->has('updated_at')
                )
        );
    }
}
