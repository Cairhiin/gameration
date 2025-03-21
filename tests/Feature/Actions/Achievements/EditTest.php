<?php

namespace Tests\Feature\Actions\Achievements;

use Tests\TestCase;
use App\Enums\RoleName;
use App\Models\Achievement;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class EditTest extends TestCase
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

    public function test_achievements_edit_page_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/achievements/' . $this->achievement->id . '/edit');

        $response->assertForbidden();
    }

    public function test_achievements_edit_page_returns_a_forbidden_response_when_user_is_a_moderator(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user, 'web')->get('/achievements/' . $this->achievement->id . '/edit');

        $response->assertForbidden();
    }

    public function test_achievements_edit_page_returns_a_successful_response_when_user_is_an_admin(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->get('/achievements/' . $this->achievement->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_achievements_edit_page_returns_an_inertia_view_with_the_achievement_when_authorized(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->get('/achievements/' . $this->achievement->id . '/edit');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Achievements/Edit')
                ->has(
                    'achievement',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('title')
                        ->has('description')
                        ->has('points')
                        ->has('image')
                        ->etc()
                )
        );
    }
}
