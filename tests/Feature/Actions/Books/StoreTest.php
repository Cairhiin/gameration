<?php

namespace Tests\Feature\Actions\Books;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Genre;
use App\Enums\BookType;
use App\Enums\RoleName;
use App\Enums\SystemMessage;
use App\Traits\HasTestFunctions;
use Illuminate\Http\UploadedFile;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Support\Facades\Storage;

class StoreTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    private array $book;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();;

        $this->book = [
            'title' => "test title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            'publisher' => $this->createPublisher()->id,
            'ISBN' => '1234567890',
            'published_at' => date('Y-m-d H:i:s'),
            'series_id' => $this->createBookSeries()->id,
            'pages' => 500,
            'time' => '05:34',
            'type' => BookType::EBOOK->value,
            'image' => null,
            'genres' => $this->createGenres(2)->pluck('id')->toArray(),
            'narrators' => $this->createNarrators(2)->pluck('id')->toArray(),
            'authors' => [$this->createAuthor()->id],
        ];
    }

    public function test_books_store_route_returns_a_forbidden_response_when_user_has_no_moderation_permission(): void
    {
        $response = $this->actingAs($this->user, 'web')->post('/books', $this->book);

        $response->assertForbidden();
    }

    public function test_books_store_route_should_fail_validation_when_publisher_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['publisher'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['publisher']);
    }

    public function test_books_store_route_should_fail_validation_when_publisher_doesnt_exist(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['publisher'] = $this->createPublisher()->id + 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['publisher']);
    }

    public function test_books_store_route_should_fail_validation_when_publisher_is_not_a_publisher(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['publisher'] = "randomID"; // Not a publisher

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['publisher']);
    }

    public function test_books_store_route_should_fail_validation_when_series_is_not_a_book_series(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['series_id'] = $this->createGame()->id; // Not a book series

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['series_id']);
    }

    public function test_books_store_route_should_fail_validation_when_ISBN_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['ISBN'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ISBN']);
    }

    public function test_books_store_route_should_fail_validation_when_ISBN_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['ISBN'] = 11123456;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ISBN']);
    }

    public function test_books_store_route_should_fail_validation_when_ISBN_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['ISBN'] = '2345';

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ISBN']);
    }

    public function test_books_store_route_should_fail_validation_when_ISBN_is_too_long(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['ISBN'] = '12345-124-12344-12';

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ISBN']);
    }

    public function test_books_store_route_should_fail_validation_when_description_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['description'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_books_store_route_should_fail_validation_when_description_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['description'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_books_store_route_should_fail_validation_when_description_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['description'] = "test";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    public function test_books_store_route_should_fail_validation_when_published_at_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['published_at'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['published_at']);
    }

    public function test_books_store_route_should_fail_validation_when_published_at_is_not_a_date(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['published_at'] = "test";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['published_at']);
    }

    public function test_books_store_route_should_fail_validation_when_published_at_is_in_the_future(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $date = Carbon::now()->addDays(1);
        $this->book['published_at'] = $date;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['published_at']);
    }

    public function test_books_store_route_should_fail_validation_when_title_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['title'] = 1;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_books_store_route_should_fail_validation_when_title_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['title'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_books_store_route_should_fail_validation_when_title_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['title'] = "te";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_books_store_route_should_fail_validation_if_pages_is_not_an_integer(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['pages'] = "200A";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pages']);
    }

    public function test_books_store_route_should_fail_validation_if_time_is_not_a_string(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['time'] = 200;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['time']);
    }

    public function test_books_store_route_should_fail_validation_if_time_is_too_short(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['time'] = "2:00";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['time']);
    }

    public function test_books_store_route_should_fail_validation_if_time_is_too_long(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['time'] = "20:00:00";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['time']);
    }

    public function test_books_store_route_should_fail_validation_if_time_is_empty_and_type_is_audiobook(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['type'] = BookType::AUDIOBOOK->value;
        $this->book['time'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['time']);
    }

    public function test_books_store_route_should_fail_validation_when_genre_field_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['genres'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['genres']);
    }

    public function test_books_store_route_should_fail_validation_when_genre_field_is_not_an_array(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);
        $genre = Genre::factory()->create();

        $this->book['genres'] = $genre->id;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['genres']);
    }

    public function test_books_store_route_should_fail_validation_when_genre_field_is_not_an_array_of_valid_genres(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);
        $genres = $this->createBooks(5); // Not genres!

        $this->book['genres'] = $genres->pluck('id')->toArray();

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['genres.0']);
    }

    public function test_books_store_route_should_fail_validation_when_authors_field_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['authors'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['authors']);
    }

    public function test_books_store_route_should_fail_validation_when_authors_field_is_not_an_array(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);
        $genre = Genre::factory()->create();

        $this->book['authors'] = $genre->id;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['authors']);
    }

    public function test_books_store_route_should_fail_validation_when_authors_field_is_not_an_array_of_valid_persons(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);
        $genres = $this->createBooks(5); // Not persons!

        $this->book['authors'] = $genres->pluck('id')->toArray();

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['authors.0']);
    }

    public function test_books_store_route_should_fail_validation_when_narrators_field_is_empty_and_type_is_audiobook(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['narrators'] = [];
        $this->book['type'] = BookType::AUDIOBOOK->value;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['narrators']);
    }

    public function test_books_store_route_should_fail_validation_when_narrators_field_is_not_an_array_of_valid_persons(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);
        $genres = $this->createBooks(5); // Not persons!

        $this->book['narrators'] = $genres->pluck('id')->toArray();

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['narrators.0']);
    }

    public function test_books_store_route_should_fail_validation_when_type_field_is_null(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['type'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_books_store_route_should_fail_validation_when_type_field_is_not_one_of_the_right_value(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['type'] = "ebooks";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_books_store_route_should_fail_validation_when_pages_field_is_null_while_type_is_physical(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['type'] = BookType::PHYSICAL->value;
        $this->book['pages'] = null;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pages']);
    }

    public function test_books_store_route_should_fail_validation_when_pages_field_is_not_an_integer(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['pages'] = "200A";

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pages']);
    }

    public function test_books_store_route_should_fail_validation_when_pages_field_is_less_than_1(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['pages'] = 0;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pages']);
    }

    public function test_book_store_route_should_fail_validation_when_image_is_too_big(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['image'] = ['image' => UploadedFile::fake()->image('image.jpg')->size(2 * 1024 * 1024 + 1)];

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_book_store_route_should_fail_validation_when_image_is_wrong_format(): void
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $this->book['image'] = ['image' => UploadedFile::fake()->create('document.pdf')];

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_books_store_route_should_store_a_book_successfully()
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);
        Storage::fake('public');

        $file = UploadedFile::fake()->image('image.jpg');

        $this->book['image'] = $file;

        $response = $this->actingAs($this->user)
            ->json('POST', '/books', $this->book);

        $book = Book::where('title', 'test title')->first();

        /** @disregard undefined method because method exists */
        Storage::disk('public')->assertExists($book->image);

        $response->assertRedirectToRoute('books.show', $book->id)->assertSessionHas('message', 'Book' . SystemMessage::STORE_SUCCESS);

        $this->assertDatabaseHas('books', [
            'title' => "test title",
            'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            'ISBN' => '1234567890',
            'pages' => 500,
            'type' => BookType::EBOOK->value,
        ]);
    }
}
