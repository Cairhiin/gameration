<?php

namespace Tests\Feature\Actions\Books\Series;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Person;
use App\Models\Series;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class StoreTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private array $series;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();;

        $this->series = [
            'title' => "test title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        ];
    }

    public function test_series_store_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->post('/books/series', $this->series);

        $response->assertForbidden();
    }

    public function test_series_store_route_should_fail_validation_when_title_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['title'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_series_store_route_should_fail_validation_when_title_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['title'] = 1234;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_series_store_route_should_fail_validation_when_title_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['title'] = "te";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_series_store_route_should_fail_validation_when_title_is_too_long(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['title'] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_series_store_route_should_fail_validation_when_title_is_not_unique(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['title'] = "test title";

        Series::factory()->create([
            'title' => $this->series['title'],
        ]);

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_series_store_route_should_fail_validation_when_description_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['description'] = 1234651;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_series_store_route_should_fail_validation_when_description_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['description'] = "Test";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_series_store_route_should_fail_validation_when_authors_is_not_an_array(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['authors'] = Person::factory()->create()->id;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['authors']);
    }

    public function test_series_store_route_should_fail_validation_when_authors_has_array_element_that_is_not_an_id(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['authors'] = [Book::factory()->create()->id, Person::factory()->create()->id];

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['authors.0']);
    }

    public function test_series_store_route_should_fail_validation_when_authors_has_array_element_that_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['authors'] = [155524, Person::factory()->create()->id];

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['authors.0']);
    }

    public function test_series_store_route_should_create_a_series(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->series['authors'] = [Person::factory()->create()->id];

        $response = $this->actingAs($this->user)
            ->json('POST', '/books/series', $this->series);

        $response->assertRedirect(route('books.series.show', ['series' => 1]));
        $this->assertDatabaseHas('series', [
            'title' => $this->series['title'],
            'description' => $this->series['description'],
        ]);
    }
}
