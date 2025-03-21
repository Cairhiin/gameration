<?php

namespace App\Actions\Achievements;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle(User $user): Collection
    {
        return $this->formatAchievements(Achievement::all(), $user->achievements);
    }

    public function asController()
    {
        $achievements = $this->handle(getAuthUser());

        return inertia('Achievements/Index', [
            'achievements' => $achievements,
        ]);
    }

    /**
     * Formats the given collection of achievements by adding a
     * boolean flag to each one indicating whether it has been
     * completed by the user.
     *
     * @param Collection $allAchievements
     * @param Collection $achievementsEarned
     * @return Collection
     */
    public function formatAchievements(Collection $allAchievements, Collection $achievementsEarned): Collection
    {
        return $allAchievements->each(function ($achievement) use ($achievementsEarned) {
            $achievement->isCompleted = $achievementsEarned->contains($achievement);

            if ($achievementsEarned->where('id', $achievement->id)->first()) {
                $achievement->unlocked_at = $achievementsEarned->where('id', $achievement->id)->first()->pivot->unlocked_at;
            }
        });
    }
}
