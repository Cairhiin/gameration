<?php

namespace Tests\Feature\Actions\Books\Image;

use Tests\TestCase;
use App\Models\Book;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use Illuminate\Http\UploadedFile;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Support\Facades\Storage;

class UpdateTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Book $book;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();;

        $this->book = $this->createBook();
    }

    public function test_books_image_update_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->put('/books/' . $this->book->id . '/image', ['image' => $this->book->image]);

        $response->assertForbidden();
    }

    public function test_books_image_update_route_successfully_updates_the_image(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('image.jpg');

        $response = $this->actingAs($this->user, 'web')->put('/books/' . $this->book->id . '/image', ['image' => $file]);

        $response->assertRedirectToRoute('books.show', $this->book->id)->assertSessionHas('message', 'Book' . SystemMessage::UPDATE_SUCCESS);

        $this->assertDatabaseHas('books', [
            'image' => 'images/' . $file->hashName(),
        ]);

        $this->book->refresh();

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertExists($this->book->image);
    }

    public function test_books_image_update_route_successfully_deletes_the_old_image(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('image.jpg');

        $response = $this->actingAs($this->user, 'web')->put('/books/' . $this->book->id . '/image', ['image' => $file]);

        $this->book->refresh();

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertExists($this->book->image);

        $newFile = UploadedFile::fake()->image('image.jpg');
        $oldFile = $this->book->image;

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertExists($oldFile);

        $response = $this->actingAs($this->user, 'web')->put('/books/' . $this->book->id . '/image', ['image' => $newFile]);

        $response->assertRedirectToRoute('books.show', $this->book->id)->assertSessionHas('message', 'Book' . SystemMessage::UPDATE_SUCCESS);

        $this->book->refresh();

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertMissing($oldFile);
    }
}
