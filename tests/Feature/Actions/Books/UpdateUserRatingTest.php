<?php

namespace Tests\Feature\Actions\Books;

use App\Enums\RoleName;
use Tests\TestCase;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class UpdateUserRatingTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_update_user_rating_updates_user_book_rating_correctly()
    {
        $book = $this->createBook();

        $this->user->assignRole(RoleName::USER);
        $this->user->books()->attach($book->id, ['user_id' => $this->user->id, 'rating' => 5]);

        $response = $this->actingAs($this->user)->post(route('books.rate', ['book' => $book]), [
            'rating' => 4,
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('books.show', $book->id)->assertSessionHas('message', 'Rating' . SystemMessage::UPDATE_SUCCESS);

        $this->assertDatabaseHas('book_user', [
            'user_id' => $this->user->id,
            'book_id' => $book->id,
            'rating' => 4,
        ]);
    }
}
