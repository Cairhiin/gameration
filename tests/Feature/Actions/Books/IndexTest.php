<?php

namespace Tests\Feature\Actions\Books;

use App\Enums\BookType;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Person;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class IndexTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_books_index_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/books');

        $response->assertStatus(200);
    }

    public function test_books_index_page_returns_an_inertia_view(): void
    {
        $books = $this->createBooks(20);
        $genres = $this->createGenres(5);

        foreach ($books as $book) {
            $book->genres()->attach($genres->random()->id);
            $book->authors()->attach(Person::factory()->create()->id);
        }

        $trendingBooks = Book::factory()->count(5)->create([
            'published_at' => now()->subDays(rand(1, 30)),
            'type' => BookType::PHYSICAL,
        ]);

        foreach ($trendingBooks as $book) {
            $book->genres()->attach($genres->random()->id);
            $book->authors()->attach(Person::factory()->create()->id);
            $book->users()->attach($this->user->id, [
                'rating' => (random_int(4, 5)),
            ]);
        }

        $response = $this->actingAs($this->user, 'web')->get('/books');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Books/Index')
                ->has('genres')
                ->has(
                    'genres.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('description')
                        ->has('books_count')
                        ->etc()
                )
                ->has('books', 5)
                ->has(
                    'books.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('title')
                        ->has('description')
                        ->has('created_at')
                        ->has('updated_at')
                        ->has('authors')
                        ->has(
                            'authors.0',
                            fn(Assert $page) => $page
                                ->has('id')
                                ->has('name')
                                ->etc()
                        )

                        ->has('published_at')
                        ->has('avg_rating')
                        ->etc()
                )
                ->has('trendingBooks', 5)
                ->has(
                    'books.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('title')
                        ->has('description')
                        ->has('created_at')
                        ->has('updated_at')
                        ->has('authors')
                        ->has(
                            'authors.0',
                            fn(Assert $page) => $page
                                ->has('id')
                                ->has('name')
                                ->etc()
                        )

                        ->has('published_at')
                        ->has('avg_rating')
                        ->etc()
                )
                ->has('popularBooks', 5)
                ->has(
                    'books.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('title')
                        ->has('description')
                        ->has('created_at')
                        ->has('updated_at')
                        ->has('authors')
                        ->has(
                            'authors.0',
                            fn(Assert $page) => $page
                                ->has('id')
                                ->has('name')
                                ->etc()
                        )

                        ->has('published_at')
                        ->has('avg_rating')
                        ->etc()
                )
                ->has(
                    'randomFriends',
                )
        );
    }

    public function test_books_index_page_returns_a_redirect_to_login_response_when_user_is_not_authenticated(): void
    {
        $response = $this->get('/books');

        $response->assertRedirectContains("login");
    }
}
