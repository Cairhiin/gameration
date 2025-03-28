<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Series;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function  test_book_model_can_create_a_book(): void
    {
        $book = Book::factory()->create();

        $this->assertDatabaseHas('books', $book->toArray());
    }

    public function test_book_model_can_retrieve_a_book(): void
    {
        $newBook = Book::factory()->create();

        $book = Book::find($newBook->id);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($newBook->id, $book->id);
        $this->assertEquals($newBook->title, $book->title);
        $this->assertEquals($newBook->description, $book->description);
        $this->assertEquals($newBook->image, $book->image);
        $this->assertEquals($newBook->released_at, $book->released_at);
        $this->assertEquals($newBook->publisher_id, $book->publisher_id);
        $this->assertEquals($newBook->user_id, $book->user_id);
        $this->assertEquals($newBook->series_id, $book->series_id);
        $this->assertEquals($newBook->type, $book->type);
    }

    public function test_book_model_has_a_creator(): void
    {
        $book = Book::factory()->create();

        $this->assertInstanceOf(User::class, $book->creator);
    }

    public function test_book_has_authors_relationship(): void
    {
        $book = Book::factory()->create();
        $author = Person::factory()->create();
        $book->authors()->attach($author->id);

        $this->assertInstanceOf(Collection::class, $book->authors);
        $this->assertInstanceOf(Person::class, $book->authors->first());
    }

    public function test_book_has_genres_relationship(): void
    {
        $book = Book::factory()->create();
        $genres = Genre::factory()->count(2)->create();
        $book->genres()->attach($genres);

        $this->assertInstanceOf(Collection::class, $book->genres);
        $this->assertInstanceOf(Genre::class, $book->genres->first());
    }

    public function test_book_has_narrators_relationship(): void
    {
        $book = Book::factory()->create();
        $narrator = Person::factory()->create();
        $book->narrators()->attach($narrator->id);

        $this->assertInstanceOf(Collection::class, $book->narrators);
        $this->assertInstanceOf(Person::class, $book->narrators->first());
    }

    public function test_book_model_has_publisher_relationship(): void
    {
        $book = Book::factory()->create();

        $this->assertInstanceOf(Publisher::class, $book->publisher);
    }

    public function test_book_model_has_series_relationship(): void
    {
        $book = Book::factory()->create();

        $this->assertInstanceOf(Series::class, $book->series);
    }

    public function test_book_model_has_users_relationship(): void
    {
        $book = Book::factory()->create();
        $book->users()->attach(User::factory()->create()->id, ['rating' => 1]);

        $this->assertInstanceOf(User::class, $book->users->first());
        $this->assertEquals(1, $book->users->first()->pivot->rating);
    }

    public function test_book_model_has_reviews_relationship(): void
    {
        $book = Book::factory()->create();
        $book->reviews()->attach(User::factory()->create()->id, ['content' => 'Lorem ipsum', 'approved' => 1]);

        $this->assertInstanceOf(User::class, $book->reviews->first());
        $this->assertEquals('Lorem ipsum', $book->reviews->first()->pivot->content);
        $this->assertEquals(1, $book->reviews->first()->pivot->approved);
    }

    public function test_book_model_avg_rating_return_the_right_value(): void
    {
        foreach ([1, 2, 3] as $user) {
            $users[] = User::factory()->create();
        }

        $book = Book::factory(['user_id' => $users[0]->id])->create();

        foreach ($users as $key => $value) {
            // Attach the user to the book with a rating: 1, 2, 3
            $book->users()->attach($value, ['rating' => $key + 1]);
        }

        $this->assertEquals(2, $book->calculateBookRating());

        foreach ($users as $key => $value) {
            // Attach the user to the book with a rating: 0, 2, 4
            $book->users()->updateExistingPivot($value, ['rating' => $key * 2]);
        }

        $this->assertEquals(3, $book->calculateBookRating());
    }

    public function test_book_model_median_rating_returns_the_right_value(): void
    {
        foreach ([1, 2, 3, 4] as $user) {
            $users[] = User::factory()->create();
        }

        $book = Book::factory(['user_id' => $users[0]->id])->create();

        foreach ($users as $key => $value) {
            // Attach the user to the book with a rating: 1, 2, 3, 4
            $book->users()->attach($value, ['rating' => $key + 1]);
        }

        $this->assertEquals(2.5, $book->calculateMedianRating());

        foreach ($users as $key => $value) {
            // Attach the user to the book with a rating: 0, 1, 2, 3
            $book->users()->updateExistingPivot($value, ['rating' => $key]);
        }

        $this->assertEquals(2, $book->calculateBookRating());
    }

    public function test_book_model_rating_count_returns_the_right_value(): void
    {
        foreach ([1, 2, 3, 4] as $rating) {
            $users[] = User::factory()->create();
        }

        $book = Book::factory(['user_id' => $users[0]->id])->create();

        foreach ($users as $key => $value) {
            // Attach the user to the book with a rating: 1, 2, 3, 4
            $book->users()->attach($value, ['rating' => $key + 1]);
        }

        $this->assertEquals(4, $book->calculateNumberOfRatings());
    }

    public function test_book_model_average_rating_is_calculated_properly_if_a_rating_is_zero(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory(['user_id' => $user1->id])->create();

        $book->users()->attach($user1, ['rating' => 0]);
        $book->users()->attach($user2, ['rating' => 1]);

        $this->assertEquals(1, $book->calculateBookRating());
    }

    public function test_book_model_median_rating_is_calculated_properly_if_a_rating_is_zero(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory(['user_id' => $user1->id])->create();

        $book->users()->attach($user1, ['rating' => 0]);
        $book->users()->attach($user2, ['rating' => 1]);

        $this->assertEquals(1, $book->calculateMedianRating());
    }

    public function test_book_model_count_is_calculated_properly_if_a_rating_is_zero(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory(['user_id' => $user1->id])->create();

        $book->users()->attach($user1, ['content' => 'test']); // Rating is 0 by default
        $book->users()->attach($user2, ['rating' => 1]);

        $this->assertEquals(1, $book->calculateNumberOfRatings());
    }
}
