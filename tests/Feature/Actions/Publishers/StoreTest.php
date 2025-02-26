<?php

namespace Tests\Feature\Actions\Publishers;

use Tests\TestCase;
use App\Models\Role;
use App\Enums\RoleName;
use App\Models\Publisher;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class StoreTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private array $publisher;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();;

        $this->publisher = [
            'name' => "test",
            'country' => "Finland",
            'year' => 2005,
            'city' =>  "Helsinki",
            'user_id' => $this->user->id
        ];
    }

    public function test_publishers_store_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->post('/publishers', $this->publisher);

        $response->assertForbidden();
    }

    public function test_publishers_store_route_should_fail_validation_when_country_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['country'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['country']);
    }

    public function test_publishers_store_route_should_fail_validation_when_country_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['country'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['country']);
    }

    public function test_publishers_store_route_should_fail_validation_when_city_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['city'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city']);
    }

    public function test_publishers_store_route_should_fail_validation_when_city_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['city'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city']);
    }

    public function test_publishers_store_route_should_fail_validation_when_year_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['year'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    }

    public function test_publishers_store_route_should_fail_validation_when_year_is_not_a_number(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['year'] = "test";

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    }

    public function test_publishers_store_route_should_fail_validation_when_year_is_too_far_in_the_past(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['year'] = 1234;

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    }

    public function test_publishers_store_route_should_fail_validation_when_year_is_in_the_future(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);
        $this->publisher['year'] = date('Y') + 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    }

    public function test_publishers_store_route_should_fail_validation_when_name_is_not_a_string(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['name'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_publishers_store_route_should_fail_validation_when_name_is_null(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['name'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_publishers_store_route_should_fail_validation_when_name_is_too_short(): void
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $this->publisher['name'] = "te";

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_publishers_store_route_should_store_a_publisher_successfully()
    {
        $this->user->roles()->sync(Role::where('name', RoleName::ADMIN)->first()->id);

        $response = $this->actingAs($this->user)
            ->json('POST', '/publishers', $this->publisher);

        $publisher = Publisher::orderBy('created_at', 'desc')->first();

        $response->assertRedirectToRoute('publishers.show', $publisher->id)->assertSessionHas('message', 'Publisher' . SystemMessage::STORE_SUCCESS);

        $this->assertDatabaseHas('publishers', [
            'name' => "test",
            'country' => "Finland",
            'year' => 2005,
            'city' =>  "Helsinki",
        ]);
    }
}
