<?php

namespace Tests\Feature\Actions\Developers;

use Tests\TestCase;
use App\Models\Role;
use App\Enums\RoleName;
use App\Models\Developer;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasSeededDatabase;

class StoreTest extends TestCase
{
    use HasSeededDatabase;
    use HasTestFunctions;

    private array $developer;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();;

        $this->developer = [
            'name' => "test",
            'country' => "Finland",
            'year' => 2005,
            'city' =>  "Helsinki",
            'user_id' => $this->user->id
        ];
    }

    public function test_developers_store_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->post('/developers', $this->developer);

        $response->assertForbidden();
    }

    public function test_developers_store_route_should_fail_validation_when_country_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['country'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['country']);
    }

    public function test_developers_store_route_should_fail_validation_when_country_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['country'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['country']);
    }

    public function test_developers_store_route_should_fail_validation_when_city_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['city'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city']);
    }

    public function test_developers_store_route_should_fail_validation_when_city_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['city'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city']);
    }

    public function test_developers_store_route_should_fail_validation_when_year_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['year'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    }

    public function test_developers_store_route_should_fail_validation_when_year_is_not_a_number(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['year'] = "test";

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    }

    public function test_developers_store_route_should_fail_validation_when_year_is_too_far_in_the_past(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['year'] = 1234;

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    }

    public function test_developers_store_route_should_fail_validation_when_year_is_in_the_future(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);
        $this->developer['year'] = date('Y') + 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    }

    public function test_developers_store_route_should_fail_validation_when_name_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['name'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_developers_store_route_should_fail_validation_when_name_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['name'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_developers_store_route_should_fail_validation_when_name_is_too_short(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->developer['name'] = "te";

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_developers_store_route_should_store_a_developer_successfully()
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('POST', '/developers', $this->developer);

        $developer = Developer::orderBy('created_at', 'desc')->first();

        $response->assertRedirectToRoute('developers.show', $developer->id)->assertSessionHas('message', 'Developer' . SystemMessage::STORE_SUCCESS);

        $this->assertDatabaseHas('developers', [
            'name' => "test",
            'country' => "Finland",
            'year' => 2005,
            'city' =>  "Helsinki",
        ]);
    }
}
