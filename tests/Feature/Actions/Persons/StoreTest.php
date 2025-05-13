<?php

namespace Tests\Feature\Actions\Persons;

use Tests\TestCase;
use App\Models\Person;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use Illuminate\Http\UploadedFile;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Support\Facades\Storage;

class StoreTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();;
    }

    public function test_persons_store_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $person = [
            'name' => 'John Doe',
            'type' => 'author',
        ];

        $response = $this->actingAs($this->user, 'web')->post('/persons', $person);

        $response->assertForbidden();
    }

    public function test_persons_store_route_should_fail_validation_when_name_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = [
            'type' => 'author',
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_persons_store_route_should_fail_validation_when_name_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = [
            'name' => 12345,
            'type' => 'author',
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_persons_store_route_should_fail_validation_when_name_is_too_long(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = [
            'name' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            'type' => 'author',
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_persons_store_route_should_fail_validation_when_type_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = [
            'name' => 'John Doe',
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_persons_store_route_should_fail_validation_when_type_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = [
            'name' => 'John Doe',
            'type' => 1245,
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_persons_store_route_should_fail_validation_when_type_is_not_one_of_the_right_values(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = [
            'name' => 'John Doe',
            'type' => "Publisher",
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_persons_store_route_should_fail_validation_when_description_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = [
            'name' => 'John Doe',
            'type' => "Publisher",
            'description' => 12345,
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_persons_store_route_should_fail_validation_when_image_is_too_big(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = [
            'name' => 'John Doe',
            'type' => "author",
        ];

        $person['image'] = ['image' => UploadedFile::fake()->image('image.jpg')->size(2 * 1024 * 1024 + 1)];

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_persons_store_route_should_fail_validation_when_image_is_wrong_format(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $person = [
            'name' => 'John Doe',
            'type' => "author",
        ];

        $person['image'] = ['image' => UploadedFile::fake()->create('document.pdf')];

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_persons_store_route_should_store_a_person_successfully()
    {

        Storage::fake('public');

        $file = UploadedFile::fake()->image('image.jpg');

        $person = [
            'name' => 'John Doe',
            'type' => "author",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            'image' => $file,
        ];

        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user)
            ->json('POST', '/persons', $person);

        $newPerson = Person::where('name', 'John Doe')->first();

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertExists($newPerson->image);

        $this->assertNotNull($newPerson);
        $this->assertEquals($newPerson->name, 'John Doe');
        $this->assertEquals($newPerson->type, 'author');

        $response->assertRedirectToRoute('persons.show', $newPerson->id)->assertSessionHas('message', 'Person' . SystemMessage::STORE_SUCCESS);

        $this->assertDatabaseHas('persons', [
            'name' => "John Doe",
            'type' => "author",
            'image' => null,
        ]);
    }
}
