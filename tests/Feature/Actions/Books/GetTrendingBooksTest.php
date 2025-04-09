<?php

namespace Tests\Feature\Actions\Books;

use Tests\TestCase;
use App\Models\Book;
use App\Enums\BookType;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use App\Actions\Books\GetTrendingBooks;

class GetTrendingBooksTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_get_trending_books_returns_up_to_five_recently_rated_books_with_highest_average_rating()
    {
        // Create 10 books with random published_at dates within the last 30 days and type as PHYSICAL
        $books = Book::factory()->count(10)->create([
            'published_at' => now()->subDays(rand(1, 30)),
            'type' => BookType::PHYSICAL,
        ]);

        // Create 5 users and attach them to each book with random ratings
        foreach ($books as $book) {
            $users = $this->createUsers(5);

            foreach ($users as $user) {
                $book->users()->attach($user->id, [
                    'rating' => (random_int(1, 5)),
                ]);
            }
        }

        // Call the GetTrendingBooks action
        $trendingBooks = GetTrendingBooks::run();

        // Sort the books by average rating and take the top 5
        $higestRatedBooks = $books->sortByDesc(function ($book) {
            return $book->getAvgRatingAttribute();
        })->take(5);

        $this->assertCount(5, $trendingBooks);
        $this->assertTrue($trendingBooks->every(function ($book) {
            return $book->type === BookType::PHYSICAL->value && $book->published_at >= now()->subDays(30);
        }));
        $this->assertEquals($higestRatedBooks->values()[0]->avg_rating, $trendingBooks->values()[0]->avg_rating);
        $this->assertEquals($higestRatedBooks->values()[1]->avg_rating, $trendingBooks->values()[1]->avg_rating);
        $this->assertEquals($higestRatedBooks->values()[2]->avg_rating, $trendingBooks->values()[2]->avg_rating);
        $this->assertEquals($higestRatedBooks->values()[3]->avg_rating, $trendingBooks->values()[3]->avg_rating);
        $this->assertEquals($higestRatedBooks->values()[4]->avg_rating, $trendingBooks->values()[4]->avg_rating);
    }
}
