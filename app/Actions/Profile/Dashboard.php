<?php

namespace App\Actions\Profile;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Traits\HasFriendList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Dashboard
{
    use AsAction;
    use HasFriendList;

    public function handle()
    {
        // ...
    }

    public function asController(): Response
    {
        $user = User::with('roles', 'friendOf', 'friendsOfMine')->find(Auth::id());
        $friends = $this->formattedFriendList($user->friends())->sortBy('username')->values()->all();

        $genres = array();

        if ($user) {
            $highestRatedGames = $user->game_user()->where('user_id', Auth::id())->orderBy('rating', 'desc')->take(5)->get();
            $latestRatedGames = $user->game_user()->where('user_id', Auth::id())->orderBy('updated_at', 'desc')->take(5)->get();
            $ratedGames = $user->game_user()->where('user_id', Auth::id())->get();

            foreach ($ratedGames as $ratedGame) {
                $genres = array_merge($genres, $ratedGame->load('game.genres')->game->genres->pluck('name')->toArray());
            }
        }

        return Inertia::render('Dashboard/Show', [
            'user' => $user,
            'friends' => $friends,
            'latestRatedGames' => $latestRatedGames->load('game'),
            'highestRatedGames' => $highestRatedGames->load('game'),
            'favoriteGenres' => collect(array_count_values($genres))->sortDesc()->take(10),
            'dashboardData' => $this->dashboardData($user),
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('user:view');
    }

    public function dashboardData($user)
    {
        return [
            'totalFriends' => $user->friends()->count(),
            'totalRatings' => $user->game_user()->where('rating', '>', '0')->count(),
            'totalReviews' => $user->game_user()->where('content', '!=', null)->count(),
            'averageRating' => $user->calculateAvgRating(),
        ];
    }
}
