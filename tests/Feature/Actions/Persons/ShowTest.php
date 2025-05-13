<?php

namespace Tests\Feature\Actions\Persons;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Person;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class ShowTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_persons_show_page_returns_a_successful_response(): void
    {
        $person = $this->createAuthor();

        $response = $this->actingAs($this->user, 'web')->get('/persons/' . $person->id);

        $response->assertStatus(200);
    }

    public function test_persons_show_page_returns_an_inertia_view(): void
    {
        $person = $this->createNarrator();

        $response = $this->actingAs($this->user, 'web')->get('/persons/' . $person->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Persons/Show')
                ->has(
                    'person',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('description')
                        ->has('image')
                        ->has('type')
                        ->has('books')
                        ->has('series')
                        ->has('avg_rating')
                        ->has('median_rating')
                        ->has('rating_count')
                        ->etc()
                )
        );
    }

    public function test_persons_show_page_shows_the_correct_average_rating(): void
    {
        $person = $this->createAuthor();

        $users = $this->createUsers(3);
        $books = $this->createBooks(5);

        foreach ($books as $index => $book) {
            $book->authors()->attach($person);
        }

        foreach ($books as $book) {
            foreach ($users as $index => $user) {
                $this->rateBook($book, $index * 2, $user);
            }
        }

        $response = $this->actingAs($this->user, 'web')->get('/persons/' . $person->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Persons/Show')
                ->where('median_rating', 3)
                ->etc()
        );
    }

    public function test_persons_show_page_shows_the_correct_ratings_count(): void
    {
        $person = $this->createAuthor();

        $users = $this->createUsers(3);
        $books = $this->createBooks(5);

        foreach ($books as $index => $book) {
            $book->authors()->attach($person);
        }

        foreach ($books as $book) {
            foreach ($users as $index => $user) {
                $this->rateBook($book, $index * 2, $user);
            }
        }


        $response = $this->actingAs($this->user, 'web')->get('/persons/' . $person->id);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Persons/Show')
                ->where('rating_count', 10)
                ->etc()
        );
    }
}
