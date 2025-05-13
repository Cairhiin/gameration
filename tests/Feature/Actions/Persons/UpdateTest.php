<?php

namespace Tests\Feature\Actions\Persons;

use Tests\TestCase;
use App\Models\Person;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class UpdateTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_persons_update_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $person = Person::factory()->create();

        $updatedPerson = [
            'name' => null,
            'type' => 'author',
        ];

        $response = $this->actingAs($this->user, 'web')->put('/persons/' . $person->id, $updatedPerson);

        $response->assertForbidden();
    }

    public function test_persons_update_route_should_fail_validation_when_name_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = Person::factory()->create();

        $updatedPerson = [
            'name' => null,
            'type' => 'author',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/persons/' . $person->id, $updatedPerson);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_persons_update_route_should_fail_validation_when_name_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = Person::factory()->create();

        $updatedPerson = [
            'name' => 12345,
            'type' => 'author',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/persons/' . $person->id, $updatedPerson);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_persons_update_route_should_fail_validation_when_name_is_too_long(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = Person::factory()->create();

        $updatedPerson = [
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'type' => 'author',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/persons/' . $person->id, $updatedPerson);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_persons_update_route_should_fail_validation_when_type_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = Person::factory()->create();

        $updatedPerson = [
            'name' => 'John Doe',
            'type' => null,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/persons/' . $person->id, $updatedPerson);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_persons_update_route_should_fail_validation_when_type_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = Person::factory()->create();

        $updatedPerson = [
            'name' => 'John Doe',
            'type' => 1245,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/persons/' . $person->id, $updatedPerson);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_persons_update_route_should_fail_validation_when_type_is_not_one_of_the_right_values(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = Person::factory()->create();

        $updatedPerson = [
            'name' => 'John Doe',
            'type' => 'publisher',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/persons/' . $person->id, $updatedPerson);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_persons_update_route_should_fail_validation_when_description_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = Person::factory()->create();

        $updatedPerson = [
            'name' => 'John Doe',
            'type' => 'author',
            'description' => 12345,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/persons/' . $person->id, $updatedPerson);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_persons_update_route_should_store_a_person_successfully()
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $person = Person::factory()->create();

        $updatedPerson = [
            'name' => 'John Doe',
            'type' => 'author',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/persons/' . $person->id, $updatedPerson);

        $newPerson = Person::where('name', 'John Doe')->first();

        $this->assertNotNull($newPerson);
        $this->assertEquals($newPerson->name, 'John Doe');
        $this->assertEquals($newPerson->type, 'author');

        $response->assertRedirectToRoute('persons.show', $newPerson->id)->assertSessionHas('message', 'Person' . SystemMessage::UPDATE_SUCCESS);

        $this->assertDatabaseHas('persons', [
            'name' => "John Doe",
            'type' => "author",
        ]);
    }
}
