<?php

namespace Tests\Feature\Actions\Books;

use Tests\TestCase;
use App\Models\Book;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;

class ShowTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Book $book;
    private Collection $users;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->book = $this->createBook();

        $this->book->genres()->attach($this->createGenre());
    }

    public function test_books_show_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id);

        $response->assertStatus(200);
    }

    public function test_books_show_page_returns_an_inertia_view(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Books/Show')
                ->has(
                    'book',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('title')
                        ->has('description')
                        ->has('series_id')
                        ->has('series', fn(Assert $page) => $page
                            ->has('id')
                            ->has('title')
                            ->has('description')
                            ->etc())
                        ->has('ISBN')
                        ->has('pages')
                        ->has('type')
                        ->has('publisher_id')
                        ->has('publisher', fn(Assert $page) => $page
                            ->has('id')
                            ->has('name')
                            ->etc())
                        ->has('published_at')
                        ->has('user_id')
                        ->has('avg_rating')
                        ->has('created_at')
                        ->has('updated_at')
                        ->has('genres.0', fn(Assert $page) => $page
                            ->has('id')
                            ->has('name')
                            ->has('description')
                            ->etc())
                        ->has('median_rating')
                        ->has('rating_count')
                        ->has('image')
                        ->etc()
                )
                ->has('last_user_ratings')
                ->has('rating')
                ->has('user_review')
                ->has('reviews')
        );
    }

    public function test_books_show_page_shows_the_correct_average_rating(): void
    {
        $users = $this->createUsers(3);

        foreach ($users as $index => $user) {
            $this->rateBook($this->book, $index * 2, $user);
        }

        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Books/Show')
                ->has(
                    'book',
                    fn(Assert $page) => $page
                        ->where('title', $this->book->title)
                        ->where('avg_rating', 3)
                        ->etc()
                )
        );
    }

    public function test_books_show_page_shows_the_correct_ratings_count(): void
    {
        $users = $this->createUsers(3);

        foreach ($users as $index => $user) {
            $this->rateBook($this->book, $index * 2, $user);
        }

        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Books/Show')
                ->has(
                    'book',
                    fn(Assert $page) => $page
                        ->where('title', $this->book->title)
                        ->etc()
                )
                ->has('last_user_ratings', 2)
                ->has('last_user_ratings.0', fn(Assert $page) => $page
                    ->where('rating', 2)
                    ->etc())
                ->has('last_user_ratings.1', fn(Assert $page) => $page
                    ->where('rating', 4)
                    ->etc())
                ->etc()
        );
    }
}
