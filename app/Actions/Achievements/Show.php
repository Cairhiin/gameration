<?php

namespace App\Actions\Achievements;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Achievement;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Show
{
    use AsAction;

    public function handle(User $user, Achievement $achievement): Achievement
    {
        $userAchievements = $user->achievements()->get();

        if ($userAchievements->contains($achievement)) {
            $achievement->setAttribute('isCompleted', true);
        } else {
            $achievement->setAttribute('isCompleted', false);
        }

        return $achievement;
    }

    public function asController(Achievement $achievement): Response
    {
        $achievement = $this->handle(getAuthUser(), $achievement);

        return Inertia::render('Achievements/Show', [
            'achievement' => $achievement,
        ]);
    }

    public function authorize(Achievement $achievement): bool
    {
        return Gate::allows('achievement:view');
    }
}
