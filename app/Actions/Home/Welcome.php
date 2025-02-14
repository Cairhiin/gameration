<?php

namespace App\Actions\Home;

use App\Models\Developer;
use App\Models\Game;
use App\Models\GameUser;
use App\Models\Publisher;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class Welcome
{
    use AsAction;

    public function handle()
    {
        return Inertia::render('Welcome', [
            'numberOfGames' => Game::count(),
            'numberOfRatings' => GameUser::count(),
            'numberOfPublishers' => Publisher::count(),
            'numberOfDevelopers' => Developer::count()
        ]);
    }
}
