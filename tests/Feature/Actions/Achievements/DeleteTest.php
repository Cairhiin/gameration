<?php

namespace Tests\Feature\Actions\Achievements;

use Tests\TestCase;
use App\Enums\RoleName;
use App\Models\Achievement;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class DeleteTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Achievement $achievement;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->achievement = $this->createAchievement();
    }

    public function test_achievements_delete_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->delete('/achievements/' . $this->achievement->id);

        $response->assertForbidden();
    }

    public function test_achievements_delete_route_returns_a_forbidden_response_when_user_is_a_moderator(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user, 'web')->delete('/achievements/' . $this->achievement->id);

        $response->assertForbidden();
    }

    public function test_achievements_delete_route_successfully_deletes_a_achievement_when_user_is_authorized(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->delete('/achievements/' . $this->achievement->id);

        $response->assertRedirectToRoute('achievements.index')->assertSessionHas('message', 'Achievement' . SystemMessage::DELETE_SUCCESS);
        $this->assertDatabaseMissing('achievements', ['id' => $this->achievement->id]);
    }
}
