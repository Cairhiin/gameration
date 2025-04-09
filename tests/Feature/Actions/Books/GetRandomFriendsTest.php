<?php

namespace Tests\Feature\Actions\Books;

use Tests\TestCase;
use App\Models\User;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use App\Actions\Books\GetRandomFriends;

class GetRandomFriendsTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_get_random_friends_returns_two_friends_with_up_to_five_recently_rated_books()
    {
        // Create five friends who accepted the request
        $users = User::factory()->count(5)->create();
        $this->user->friendsOfMine()->attach(
            $users->pluck('id')->toArray(),
            ['accepted' => true]
        );

        // Create 10 books for each friend
        foreach ($users as $friend) {
            $friend->books()->attach($this->createBooks(10)->pluck('id')->toArray());
        }

        $randomFriends = GetRandomFriends::run($this->user);

        $this->assertCount(2, $randomFriends);
        $this->assertCount(5, $randomFriends[0]->books);
        $this->assertCount(5, $randomFriends[1]->books);
    }

    public function test_get_random_friends_returns_one_friend_if_only_one_friend_exists_with_up_to_five_recently_rated_books()
    {
        // Create one friend who accepted the request
        $friend = $this->createUser();

        $this->user->friendsOfMine()->attach(
            $friend->id,
            ['accepted' => true]
        );

        // Create 10 books for the friend
        $friend->books()->attach($this->createBooks(10)->pluck('id')->toArray());

        $randomFriends = GetRandomFriends::run($this->user);

        $this->assertCount(1, $randomFriends);
        $this->assertCount(5, $randomFriends[0]->books);
    }

    public function test_get_random_friends_returns_no_friends_without_errors_if_no_friends_exist()
    {
        $randomFriends = GetRandomFriends::run($this->user);

        $this->assertCount(0, $randomFriends);
    }

    public function test_get_random_friends_returns_without_errors_if_friend_has_less_than_five_recently_rated_books()
    {
        // Create one friend who accepted the request
        $friend = $this->createUser();

        $this->user->friendsOfMine()->attach(
            $friend->id,
            ['accepted' => true]
        );

        // Create 2 books for the friend
        $friend->books()->attach($this->createBooks(2)->pluck('id')->toArray());

        $randomFriends = GetRandomFriends::run($this->user);

        $this->assertCount(1, $randomFriends);
        $this->assertCount(2, $randomFriends[0]->books);
    }

    public function test_get_random_friends_returns_friends_with_at_least_one_book_rated()
    {
        // Create five friends who accepted the request
        $users = User::factory()->count(5)->create();
        $this->user->friendsOfMine()->attach(
            $users->pluck('id')->toArray(),
            ['accepted' => true]
        );

        $randomFriends = GetRandomFriends::run($this->user);

        $this->assertCount(0, $randomFriends);
    }
}
