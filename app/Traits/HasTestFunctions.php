<?php

namespace App\Traits;

use App\Models\Game;
use App\Models\Role;
use App\Models\User;
use App\Enums\RoleName;
use App\Models\GameUser;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

trait HasTestFunctions
{
    private User $user;
    private Collection $games;

    public function createUserWithRoleAndPermissions(): void
    {
        $this->user = User::factory()
            ->create();

        $this->user->roles()->sync(Role::where('name', RoleName::USER->value)->first());

        foreach (Permission::pluck('name') as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }

    public function createGames(int $number): Collection
    {
        return Game::factory(['user_id' => $this->user->id])->count($number)->create();
    }

    public function rateGame(Game $game, int $rating): Game
    {
        if ($rating < 0 || $rating > 5) {
            return $game;
        }

        $gameUser = $game->users()->find($this->user->id);

        if (!$gameUser) {
            $gameUser = GameUser::create([
                'game_id' => $game->id,
                'user_id' => $this->user->id,
            ]);
        }

        $game->users()->updateExistingPivot($this->user->id, ['rating' => $rating, 'updated_at' => now()]);

        return $game;
    }
}
