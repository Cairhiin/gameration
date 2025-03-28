<?php

namespace App\Traits;

use App\Models\Book;
use App\Models\Game;
use App\Models\Role;
use App\Models\User;
use App\Models\Genre;
use App\Enums\RoleName;
use App\Models\GameUser;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Permission;
use App\Models\Achievement;
use App\Models\BookUser;
use App\Models\Person;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

trait HasTestFunctions
{
    private User $user;

    public function createUserWithRoleAndPermissions(): User
    {
        $this->user = User::factory()
            ->create();

        $this->user->roles()->sync(Role::where('name', RoleName::USER->value)->first());

        foreach (Permission::pluck('name') as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }

        return $this->user;
    }

    public function addRoleToUser(User $user, RoleName $role): User
    {
        $user->roles()->sync(Role::where('name', $role->value)->first());

        return $user;
    }

    public function createGames(int $number, ?User $user = null): Collection
    {
        return Game::factory(['user_id' => $user->id ?? $this->user->id])->count($number)->create();
    }

    public function createGame(): Game
    {
        return $this->createGames(1)->first();
    }

    public function createBooks(int $number, ?User $user = null): Collection
    {
        return Book::factory(['user_id' => $user->id ?? $this->user->id])->count($number)->create();
    }

    public function createAuthors(int $number, ?User $user = null): Collection
    {
        return Person::factory()->count($number)->create();
    }

    public function createAuthor(): Person
    {
        return $this->createAuthors(1)->first();
    }

    public function createNarrators(int $number, ?User $user = null): Collection
    {
        return Person::factory()->count($number)->create();
    }

    public function createNarrator(): Person
    {
        return $this->createNarrators(1)->first();
    }

    public function createMultipleBookSeries(int $number, ?User $user = null): Collection
    {
        return Book::factory(['user_id' => $user->id ?? $this->user->id])->count($number)->create();
    }

    public function createBookSeries(): Book
    {
        return $this->createMultipleBookSeries(1)->first();
    }

    public function createBook(): Book
    {
        return $this->createBooks(1)->first();
    }

    public function createAchievements(int $number, ?User $user = null): Collection
    {
        return Achievement::factory()->count($number)->create();
    }

    public function createAchievement(): Achievement
    {
        return $this->createAchievements(1)->first();
    }

    public function rateGame(Game $game, int $rating, ?User $user = null): Game
    {
        if ($rating <= 0 || $rating > 5) {
            return $game;
        }

        $ratingUser = $user ?? $this->user;

        $gameUser = $game->users()->find($ratingUser->id);

        if (!$gameUser) {
            $gameUser = GameUser::create([
                'game_id' => $game->id,
                'user_id' => $ratingUser->id,
            ]);
        }

        $game->users()->updateExistingPivot($ratingUser->id, ['rating' => $rating, 'updated_at' => now()]);

        return $game;
    }

    public function rateBook(Book $book, int $rating, ?User $user = null): Book
    {
        if ($rating <= 0 || $rating > 5) {
            return $book;
        }

        $ratingUser = $user ?? $this->user;

        $bookRater = $book->users()->find($ratingUser->id);

        if (!$bookRater) {
            $bookRater = BookUser::create([
                'game_id' => $book->id,
                'user_id' => $ratingUser->id,
            ]);
        }

        $book->users()->updateExistingPivot($ratingUser->id, ['rating' => $rating, 'updated_at' => now()]);

        return $book;
    }

    public function rateGames(Collection $games, int $rating, ?User $user = null): Collection
    {
        return $games->map(function (Game $game) use ($rating, $user) {
            return $this->rateGame($game, $rating, $user);
        });
    }

    public function createDevelopers(int $number, ?User $user = null): Collection
    {
        return Developer::factory(['user_id' => $user->id ?? $this->user->id])->count($number)->create();
    }

    public function createDeveloper(): Developer
    {
        return $this->createDevelopers(1)->first();
    }

    public function createPublishers(int $number, ?User $user = null): Collection
    {
        return Publisher::factory(['user_id' => $user->id ?? $this->user->id])->count($number)->create();
    }

    public function createPublisher(): Publisher
    {
        return $this->createPublishers(1)->first();
    }

    public function createUsers(int $number): Collection
    {
        $users = User::factory()->count($number)->create();

        foreach ($users as $user) {
            $user->roles()->sync(Role::where('name', RoleName::USER->value)->first());

            foreach (Permission::pluck('name') as $permission) {
                Gate::define($permission, function ($user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }

        return $users;
    }

    public function createUser(): User
    {
        return $this->createUsers(1)->first();
    }

    public function createGenres(int $number): Collection
    {
        return Genre::factory()->count($number)->create();
    }

    public function createGenre(): Genre
    {
        return $this->createGenres(1)->first();
    }

    public function createReviews(int $number, Game $game, ?User $user = null): Collection
    {
        return GameUser::factory([
            'user_id' => $user->id ?? $this->user->id,
            'game_id' => $game->id
        ])->count($number)->create();
    }

    public function createReview(Game $game, ?User $user = null): GameUser
    {
        return $this->createReviews(1, $game, $user)->first();
    }
}
