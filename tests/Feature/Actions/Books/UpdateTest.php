<?php

namespace Tests\Feature\Actions\Books;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Book;
use App\Enums\BookType;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use Illuminate\Http\UploadedFile;
use App\Traits\HasRolesAndPermissions;

class UpdateTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private Book $book;
    private array $bookData;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();;

        $this->book = $this->createBook();

        $this->bookData = [
            'title' => "test title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            'publisher_id' => $this->createPublisher()->id,
            'ISBN' => '1234567890',
            'published_at' => date('Y-m-d H:i:s'),
            'series_id' => $this->createBookSeries()->id,
            'pages' => 500,
            'type' => BookType::EBOOK->value,
            'image' => null,
            'genres' => $this->createGenres(2)->pluck('id')->toArray(),
            'narrators' => $this->createNarrators(2)->pluck('id')->toArray(),
            'authors' => [$this->createAuthor()->id],
        ];
    }

    public function test_books_update_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->put('/books/' . $this->book->id, $this->bookData);

        $response->assertForbidden();
    }

    public function test_books_update_route_should_fail_validation_when_publisher_id_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['publisher_id'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['publisher_id']);
    }

    public function test_books_update_route_should_fail_validation_when_publisher_doesnt_exist(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['publisher_id'] = $this->createPublisher()->id + 1;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['publisher_id']);
    }

    public function test_books_update_route_should_fail_validation_when_publisher_is_not_a_publisher(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['publisher_id'] = $this->createGame()->id; // Not a publisher

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['publisher_id']);
    }

    public function test_books_update_route_should_fail_validation_when_series_is_not_a_book_series(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['series_id'] = $this->createGame()->id; // Not a book series

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['series_id']);
    }

    public function test_books_update_route_should_fail_validation_when_ISBN_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['ISBN'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ISBN']);
    }

    public function test_books_update_route_should_fail_validation_when_ISBN_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['ISBN'] = 11123456;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ISBN']);
    }

    public function test_books_update_route_should_fail_validation_when_ISBN_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['ISBN'] = '2345';

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ISBN']);
    }

    public function test_books_update_route_should_fail_validation_when_ISBN_is_too_long(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['ISBN'] = '12345-124-12344-12';

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ISBN']);
    }

    public function test_books_update_route_should_fail_validation_when_description_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['description'] = 1;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_books_update_route_should_fail_validation_when_description_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['description'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_books_update_route_should_fail_validation_when_description_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['description'] = "test";

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_books_update_route_should_fail_validation_when_published_at_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['published_at'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['published_at']);
    }

    public function test_books_update_route_should_fail_validation_when_published_at_is_not_a_date(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['published_at'] = "test";

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['published_at']);
    }

    public function test_books_update_route_should_fail_validation_when_published_at_is_in_the_future(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $date = Carbon::now()->addDays(1);
        $this->bookData['published_at'] = $date;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['published_at']);
    }

    public function test_books_update_route_should_fail_validation_when_title_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['title'] = 1;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_books_update_route_should_fail_validation_when_title_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['title'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_books_update_route_should_fail_validation_when_title_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['title'] = "te";

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_books_update_route_should_fail_validation_when_genre_field_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['genres'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['genres']);
    }

    public function test_books_update_route_should_fail_validation_when_genre_field_is_not_an_array_of_valid_genres(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);
        $genres = $this->createBooks(5); // Not genres!

        $this->bookData['genres'] = $genres->pluck('id')->toArray();

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['genres.0']);
    }

    public function test_books_update_route_should_fail_validation_when_authors_field_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['authors'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['authors']);
    }

    public function test_books_update_route_should_fail_validation_when_authors_field_is_not_an_array_of_valid_persons(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);
        $genres = $this->createBooks(5); // Not persons!

        $this->bookData['authors'] = $genres->pluck('id')->toArray();

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['authors.0']);
    }

    public function test_books_update_route_should_fail_validation_when_narrators_field_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['narrators'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['narrators']);
    }

    public function test_books_update_route_should_fail_validation_when_narrators_field_is_not_an_array_of_valid_persons(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);
        $genres = $this->createBooks(5); // Not persons!

        $this->bookData['narrators'] = $genres->pluck('id')->toArray();

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['narrators.0']);
    }

    public function test_books_update_route_should_fail_validation_when_type_field_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['type'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_books_update_route_should_fail_validation_when_type_field_is_not_one_of_the_right_value(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['type'] = "ebooks";

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_books_update_route_should_fail_validation_when_pages_field_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['pages'] = null;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pages']);
    }

    public function test_books_update_route_should_fail_validation_when_pages_field_is_not_an_integer(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['pages'] = "200A";

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pages']);
    }

    public function test_books_update_route_should_fail_validation_when_pages_field_is_less_than_1(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['pages'] = 0;

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pages']);
    }

    public function test_book_update_route_should_fail_validation_when_image_is_too_big(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['image'] = ['image' => UploadedFile::fake()->image('image.jpg')->size(2 * 1024 * 1024 + 1)];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_book_update_route_should_fail_validation_when_image_is_wrong_format(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->bookData['image'] = ['image' => UploadedFile::fake()->create('document.pdf')];

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_books_update_route_should_update_a_book_successfully()
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user)
            ->json('PUT', '/books/' . $this->book->id, $this->bookData);

        $book = Book::where('title', 'test title')->first();

        $response->assertRedirectToRoute('books.show', $book->id)->assertSessionHas('message', 'Book' . SystemMessage::UPDATE_SUCCESS);

        $this->assertDatabaseHas('books', [
            'title' => "test title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            'ISBN' => '1234567890',
            'pages' => 500,
            'type' => BookType::EBOOK->value,
        ]);
    }
}
