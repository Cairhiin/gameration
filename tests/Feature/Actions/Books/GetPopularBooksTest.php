<?php

namespace Tests\Feature\Actions\Books;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Enums\BookType;
use App\Traits\HasTestFunctions;
use App\Actions\Books\GetPopularBooks;
use App\Traits\HasRolesAndPermissions;

class GetPopularBooksTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_get_popular_books_route_returns_five_books_that_are_rated_higher_or_equal_to_4_if_there_are_enough()
    {
        $books = Book::factory()->count(100)->create([
            'type' => BookType::PHYSICAL,
        ]);

        $users = User::factory()->count(5)->create();

        $books->each(function ($book) use ($users) {
            // Attach each book to each user with a random rating between 3 and 5
            // This simulates the users rating the book
            $users->each(function ($user) use ($book) {
                $user->books()->attach($book->id, [
                    'rating' => rand(3, 5),
                ]);
            });
        });

        $popularBooks = GetPopularBooks::run();

        $this->assertCount(5, $popularBooks);
        $this->assertTrue($popularBooks->every(function ($book) {
            return $book->avg_rating >= 4;
        }));
    }

    public function test_get_popular_books_route_returns_always_5_books_even_when_there_are_not_enough_above_4()
    {
        $books = Book::factory()->count(100)->create([
            'type' => BookType::PHYSICAL,
        ]);

        $users = User::factory()->count(5)->create();

        for ($i = 0; $i < 100; $i++) {
            // Attach each book to each user with a random rating between 1 and 3
            // This simulates the users rating the book
            if ($i >= 3) {
                $users->each(function ($user) use ($books, $i) {
                    $user->books()->attach($books[$i]->id, [
                        'rating' => 3.0,
                    ]);
                });
                continue;
            }

            // Attach each book to each user with a random rating between 4 and 5
            $users->each(function ($user) use ($books, $i) {
                $user->books()->attach($books[$i]->id, [
                    'rating' => rand(4, 5),
                ]);
            });
        }

        $popularBooks = GetPopularBooks::run();

        $this->assertCount(5, $popularBooks);
        $this->assertTrue($popularBooks->every(function ($book) {
            return $book->avg_rating >= 2.5;
        }));
    }

    public function test_get_popular_books_route_returns_only_physical_books()
    {
        $books = Book::factory()->count(100)->create([
            'type' => BookType::PHYSICAL,
        ]);

        $users = User::factory()->count(5)->create();

        $books->each(function ($book) use ($users) {
            // Attach each book to each user with a random rating between 3 and 5
            // This simulates the users rating the book
            $users->each(function ($user) use ($book) {
                $user->books()->attach($book->id, [
                    'rating' => rand(3, 5),
                ]);
            });
        });

        $popularBooks = GetPopularBooks::run();

        $this->assertTrue($popularBooks->every(function ($book) {
            return $book->type === BookType::PHYSICAL->value;
        }));
    }
}
