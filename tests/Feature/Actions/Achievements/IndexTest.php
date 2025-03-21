<?php

namespace Tests\Feature\Actions\Achievements;

use Tests\TestCase;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class IndexTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_achievements_index_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/achievements');

        $response->assertStatus(200);
    }

    public function test_achievements_index_page_returns_an_inertia_view(): void
    {
        $this->createAchievements(5);

        $response = $this->actingAs($this->user, 'web')->get('/achievements');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Achievements/Index')
                ->has('achievements')
                ->has(
                    'achievements.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('description')
                        ->has('points')
                        ->has('image')
                        ->where('isCompleted', false)
                        ->has('created_at')
                        ->has('updated_at')
                        ->etc()
                )
        );
    }

    public function test_achievements_index_page_returns_an_inertia_view_with_correct_achievement_data(): void
    {
        $achievements = $this->createAchievements(5);

        $this->user->achievements()->attach($achievements->first()->id);

        $response = $this->actingAs($this->user, 'web')->get('/achievements');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Achievements/Index')
                ->has('achievements')
                ->has(
                    'achievements.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('description')
                        ->has('points')
                        ->has('image')
                        ->where('isCompleted', true)
                        ->has('created_at')
                        ->has('updated_at')
                        ->etc()
                )
        );
    }

    public function test_achievements_index_page_contains_the_achievements(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/achievements');

        $response->assertViewMissing('No achievements found');
    }
}
