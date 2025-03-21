<?php

namespace Tests\Feature\Actions\Achievements;

use Tests\TestCase;
use App\Enums\RoleName;
use App\Models\Achievement;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use Illuminate\Http\UploadedFile;
use App\Traits\HasRolesAndPermissions;

class StoreTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private array $achievement;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();;

        $this->achievement = [
            'title' => "test title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            'points' => 20,
            'image' =>  UploadedFile::fake()->image('image.jpg'),
        ];
    }

    public function test_achievements_store_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->post('/achievements', $this->achievement);
        $response->assertForbidden();
    }

    public function test_achievements_store_route_returns_a_forbidden_response_when_user_is_a_moderator(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user, 'web')->post('/achievements', $this->achievement);
        $response->assertForbidden();
    }

    public function test_achievements_store_route_should_fail_validation_when_title_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['title'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_achievements_store_route_should_fail_validation_when_title_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['title'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_achievements_store_route_should_fail_validation_when_title_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['title'] = "te";

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_achievements_store_route_should_fail_validation_when_title_is_too_long(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['title'] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec libero nec libero. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec libero nec libero. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec libero nec libero.";

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_achievements_store_route_should_fail_validation_when_description_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['description'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_achievements_store_route_should_fail_validation_when_description_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['description'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_achievements_store_route_should_fail_validation_when_description_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['description'] = "te";

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_achievements_store_route_should_fail_validation_when_points_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['points'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['points']);
    }

    public function test_achievements_store_route_should_fail_validation_when_points_is_not_a_number(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['points'] = "test";

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['points']);
    }

    public function test_achievements_store_route_should_fail_validation_when_image_is_not_a_file(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['image'] = "test";

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_achievements_store_route_should_fail_validation_when_image_is_not_a_jpg_or_png_or_gif(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $this->achievement['image'] = UploadedFile::fake()->create('document.pdf');

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_achievements_store_route_should_store_a_achievement_successfully()
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user)
            ->json('POST', '/achievements', $this->achievement);

        $achievement = Achievement::first();

        $response->assertRedirectToRoute('achievements.show', $achievement->id)->assertSessionHas('message', 'Achievement' . SystemMessage::STORE_SUCCESS);

        $this->assertDatabaseHas('achievements', [
            'title' => "test title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            'points' => 20,
            'image' =>  $achievement->image,
        ]);
    }
}
