<?php

namespace Tests\Feature\Actions\Books;

use Tests\TestCase;
use App\Models\Book;
use App\Enums\BookType;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class EditTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Book $book;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->book = Book::factory()->create([
            'title' => "test book title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            'publisher_id' => $this->createPublisher()->id,
            'ISBN' => '1234567890',
            'published_at' => date('Y-m-d H:i:s'),
            'series_id' => $this->createBookSeries()->id,
            'pages' => 500,
            'type' => BookType::EBOOK->value,
            'image' => null,
        ]);
    }

    public function test_books_edit_page_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id . '/edit');

        $response->assertStatus(403);
    }

    public function test_books_edit_page_returns_a_successful_response_when_user_is_a_moderator(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_books_edit_page_returns_a_successful_response_when_user_is_an_admin(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_books_edit_page_returns_an_inertia_view_with_the_book_when_authorized(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book->genres()->attach($this->createGenre());

        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id . '/edit');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Books/Edit')
                ->has(
                    'book',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->where('title', $this->book->title)
                        ->where('description', $this->book->description)
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
                        ->has('created_at')
                        ->has('updated_at')
                        ->has('genres.0', fn(Assert $page) => $page
                            ->has('id')
                            ->has('name')
                            ->has('description')
                            ->etc())
                        ->has('image')
                        ->etc()
                )
        );
    }
}
