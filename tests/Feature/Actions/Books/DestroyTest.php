<?php

namespace Tests\Feature\Actions\Books;

use Tests\TestCase;
use App\Models\Book;
use App\Enums\BookType;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use Illuminate\Http\UploadedFile;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Support\Facades\Storage;

class DeleteTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Book $book;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();

        $this->book = $this->createBook();
    }

    public function test_books_delete_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->delete('/books/' . $this->book->id);
        $response->assertStatus(403);

        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user, 'web')->delete('/books/' . $this->book->id);
        $response->assertStatus(403);
    }

    public function test_books_delete_route_successfully_deletes_a_book_when_user_is_authorized(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->delete('/books/' . $this->book->id);

        $response->assertRedirectToRoute('books.index')->assertSessionHas('message', 'Book' . SystemMessage::DELETE_SUCCESS);
        $this->assertDatabaseMissing('books', ['id' => $this->book->id]);
    }

    public function test_books_delete_route_successfully_deletes_a_books_image_when_user_is_authorized(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('image.jpg');

        $book = [
            'title' => "test title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            'publisher_id' => $this->createPublisher()->id,
            'ISBN' => '1234567890',
            'published_at' => date('Y-m-d H:i:s'),
            'series_id' => $this->createBookSeries()->id,
            'pages' => 500,
            'type' => BookType::EBOOK->value,
            'image' =>  $file,
            'genres' => $this->createGenres(2)->pluck('id')->toArray(),
            'narrators' => $this->createNarrators(2)->pluck('id')->toArray(),
            'authors' => [$this->createAuthor()->id],
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $book);
        $this->assertDatabaseHas('books', ['title' => "test title"]);

        $this->book = Book::where('title', 'test title')->first();

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertExists($this->book->image);

        $response = $this->actingAs($this->user, 'web')->delete('/books/' . $this->book->id);

        $response->assertRedirectToRoute('books.index')->assertSessionHas('message', 'Book' . SystemMessage::DELETE_SUCCESS);
        $this->assertDatabaseMissing('books', ['id' => $this->book->id]);

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertMissing($this->book->image);
    }
}
