<?php

namespace Tests\Feature\Actions\Books\Image;

use Tests\TestCase;
use App\Models\Book;
use App\Enums\BookType;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
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

        Storage::fake('public');

        $file = UploadedFile::fake()->image('image.jpg');

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

    public function test_books_image_edit_page_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id . '/image/edit');

        $response->assertStatus(403);
    }

    public function test_books_edit_page_returns_a_successful_response_when_user_is_a_moderator(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id . '/image/edit');

        $response->assertStatus(200);
    }

    public function test_books_image_edit_page_returns_a_successful_response_when_user_is_an_admin(): void
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id . '/image/edit');

        $response->assertStatus(200);
    }

    public function test_books_image_edit_page_returns_an_inertia_view_with_the_book_when_authorized(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        Storage::fake('public');

        $this->book->update([
            'image' => UploadedFile::fake()->image('image.jpg')
        ]);

        $response = $this->actingAs($this->user, 'web')->get('/books/' . $this->book->id . '/image/edit');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Books/Image/Edit')
                ->has(
                    'image',
                )
        );
    }
}
