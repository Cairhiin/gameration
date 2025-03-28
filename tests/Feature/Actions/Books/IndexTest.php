<?php

namespace Tests\Feature\Actions\Books;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Publisher;
use App\Models\Series;
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
        $publisher = $this->createPublisher();
        $series = Series::factory()->create();
        $books = Book::factory()->count(20)->create(['series_id' => $series->id, 'publisher_id' => $publisher->id]);

        foreach ($books as $book) {
            $book->genres()->attach(Genre::factory()->create());
            $book->authors()->attach(Person::factory()->create());
            $book->narrators()->attach(Person::factory()->create());
        }

        $response = $this->actingAs($this->user, 'web')->get('/books');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Books/Index')
                ->has('books')
                ->has(
                    'books.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('title')
                        ->has('description')
                        ->has('image')
                        ->has('user_id')
                        ->has('publisher', fn(Assert $page) => $page
                            ->has('id')
                            ->has('name')
                            ->etc())
                        ->has('ISBN')
                        ->has('series', fn(Assert $page) => $page
                            ->has('id')
                            ->has('title')
                            ->has('description')
                            ->etc())
                        ->has('avg_rating')
                        ->has('genres.0', fn(Assert $page) => $page
                            ->has('id')
                            ->has('name')
                            ->has('description')
                            ->etc())
                        ->has('median_rating')
                        ->has('rating_count')
                        ->has('created_at')
                        ->has('updated_at')
                        ->has('authors')
                        ->has('authors.0', fn(Assert $page) => $page
                            ->has('id')
                            ->has('name')
                            ->etc())
                        ->has('narrators')
                        ->has('narrators.0', fn(Assert $page) => $page
                            ->has('id')
                            ->has('name')
                            ->etc())
                        ->has('published_at')
                        ->etc()
                )
        );
    }

    public function test_books_index_page_returns_a_redirect_to_login_response_when_user_is_not_authenticated(): void
    {
        $response = $this->get('/books');

        $response->assertRedirectContains("login");
    }
}
