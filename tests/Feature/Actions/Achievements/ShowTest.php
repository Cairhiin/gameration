<?php

namespace Tests\Feature\Actions\Achievements;

use App\Enums\RoleName;
use App\Models\Achievement;
use App\Traits\HasRolesAndPermissions;
use App\Traits\HasTestFunctions;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class ShowTest extends TestCase
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

    public function test_achievements_show_page_returns_a_successful_response(): void
    {
        $this->user->achievements()->attach($this->achievement->id);
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->get('/achievements/' . $this->achievement->id);

        $response->assertStatus(200);
    }

    public function test_achievements_show_page_returns_an_inertia_view(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/achievements/' . $this->achievement->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Achievements/Show')
                ->has(
                    'achievement',
                    fn(Assert $page) => $page
                        ->where('id', $this->achievement->id)
                        ->where('title', $this->achievement->title)
                        ->where('description', $this->achievement->description)
                        ->where('points', $this->achievement->points)
                        ->where('created_at', $this->achievement->created_at->toISOString())
                        ->where('updated_at', $this->achievement->updated_at->toISOString())
                        ->where('isCompleted', false)
                        ->etc()
                )
        );
    }

    public function test_achievements_show_page_returns_an_inertia_view_with_correct_user_achievement_data(): void
    {
        $this->user->achievements()->attach($this->achievement->id);

        $response = $this->actingAs($this->user, 'web')->get('/achievements/' . $this->achievement->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Achievements/Show')
                ->has(
                    'achievement',
                    fn(Assert $page) => $page
                        ->where('isCompleted', true)
                        ->etc()
                )
        );
    }
}
