<?php

namespace Tests\Feature\Actions\Books;

use Tests\TestCase;
use App\Models\Book;
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

        $this->book['image'] = UploadedFile::fake()->image('image.jpg');

        Storage::disk('public')->put($this->book->image, UploadedFile::fake()->image('image.jpg'));
        //Storage::disk('public')->assertExists($this->book->image);

        $response = $this->actingAs($this->user, 'web')->delete('/books/' . $this->book->id);

        $response->assertRedirectToRoute('books.index')->assertSessionHas('message', 'Book' . SystemMessage::DELETE_SUCCESS);
        $this->assertDatabaseMissing('books', ['id' => $this->book->id]);

        //Storage::disk('public')->assertMissing($this->book->image);
    }
}
