<?php

namespace Tests\Feature\Actions\Books\Image;

use Tests\TestCase;
use App\Models\Book;
use App\Enums\BookType;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use Illuminate\Http\UploadedFile;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Support\Facades\Storage;

class DestroyTest extends TestCase
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

    public function test_destroy_route_returns_forbidden_response_when_user_does_not_have_admin_role()
    {
        $response = $this->actingAs($this->user, 'web')->delete('/books/' . $this->book->id . '/image');

        $response->assertForbidden();

        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user, 'web')->delete('/books/' . $this->book->id . '/image');

        $response->assertForbidden();
    }

    public function test_destroy_route_successfully_deletes_the_image()
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        Storage::fake('public');

        $this->book->update([
            'image' => UploadedFile::fake()->image('image.jpg')->store('images', 'public')
        ]);

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertExists($this->book->image);

        $response = $this->actingAs($this->user, 'web')->delete('/books/' . $this->book->id . '/image');

        $response->assertRedirectToRoute('books.show', $this->book->id)->assertSessionHas('message', 'Book' . SystemMessage::DELETE_SUCCESS);

        $this->assertDatabaseHas('books', [
            'image' => null,
        ]);

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertMissing($this->book->image);
    }
}
