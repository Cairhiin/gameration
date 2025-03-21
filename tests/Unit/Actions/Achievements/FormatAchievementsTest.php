<?php

namespace Tests\Unit\Actions\Achievements;

use App\Models\Achievement;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormatAchievementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_format_achievements_method_returns_the_expected_value(): void
    {
        $user = User::factory()->create();

        Achievement::factory()->count(3)->create();

        $user->achievements()->attach(Achievement::first()->id, ['unlocked_at' => now()]);

        $formattedAchievements = (new \App\Actions\Achievements\Index)->formatAchievements(Achievement::all(), $user->achievements);

        $this->assertEquals($formattedAchievements->first()->isCompleted, true);
        $this->assertEquals($formattedAchievements->first()->unlocked_at, $user->achievements->first()->pivot->unlocked_at);

        $this->assertEquals($formattedAchievements->last()->isCompleted, false);
        $this->assertArrayNotHasKey('unlocked_at', $formattedAchievements->last()->toArray());
    }
}
